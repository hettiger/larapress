<?php namespace Larapress\Tests\Filters;

use Captcha;
use InvalidArgumentException;
use Larapress\Filters\Special\ForceHumanFilter;
use Larapress\Tests\TestCase;
use Permission;
use Route;
use Config;

class FilterTest extends TestCase
{
    private $backend_route;

    public function setUp()
    {
        parent::setUp();

        Route::enableFilters();

        $this->backend_route = Config::get('larapress.urls.backend');
    }

    /*
    |--------------------------------------------------------------------------
    | force.human Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the force.human filter
    |
    */

    public function test_can_return_null_if_captcha_is_not_required()
    {
        Captcha::shouldReceive('isRequired')->once()->andReturn(false);

        $filter = new ForceHumanFilter;
        $this->assertInternalType('null', $filter->filter());
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Cannot redirect to an empty URL.
     */
    public function test_can_redirect_back_if_the_captcha_is_required()
    {
        Captcha::shouldReceive('isRequired')->once()->andReturn(true);

        $filter = new ForceHumanFilter;
        $filter->filter();
    }

    public function test_can_flash_an_error_message_to_the_session_if_the_captcha_is_required()
    {
        Captcha::shouldReceive('isRequired')->once()->andReturn(true);

        $filter = new ForceHumanFilter;

        try
        {
            $filter->filter();
        }
        catch (InvalidArgumentException $e)
        {
            $this->assertEquals('Cannot redirect to an empty URL.', $e->getMessage());
        }

        $this->assertSessionHas('error', 'Please verify that you are human first.');
    }

    /*
    |--------------------------------------------------------------------------
    | access.backend Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the access.backend filter
    |
    */

    public function test_can_redirect_on_missing_permissions()
    {
        Permission::shouldReceive('has')->once()->andThrow('\Larapress\Exceptions\PermissionMissingException', 'error');

        $this->route('GET', 'larapress.cp.dashboard.get');

        $this->assertRedirectedToRoute('larapress.home.login.get');
        $this->assertSessionHas('error', 'error');
    }

    public function test_can_access_on_given_permissions()
    {
        Permission::shouldReceive('has')->once()->andReturn(true);

        $this->route('GET', 'larapress.cp.dashboard.get');

        $this->assertResponseOk();
    }

    /*
    |--------------------------------------------------------------------------
    | force.ssl Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the force.ssl filter
    |
    */

    public function test_can_remain_silent_if_the_config_entry_is_set_to_false()
    {
        Config::set('larapress.settings.ssl', false);

        $this->route('GET', 'larapress.home.login.get');

        $this->assertResponseOk();
    }

    public function test_can_redirect_to_secure_urls_if_the_config_entry_is_set_to_true()
    {
        Config::set('larapress.settings.ssl', true);
        $request = $this->backend_route . '/login';
        $expected_redirect_url = url($request, array(), true);

        $this->route('GET', 'larapress.home.login.get');

        $this->assertRedirectedTo($expected_redirect_url);
    }

}
