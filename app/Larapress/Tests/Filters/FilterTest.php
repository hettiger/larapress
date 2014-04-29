<?php namespace Larapress\Tests\Filters;

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
    | access.backend Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the access.backend filter
    |
    */

    public function test_can_redirect_on_missing_permissions()
    {
        Permission::shouldReceive('has')->once()->andThrow('\Larapress\Exceptions\PermissionMissingException', 'error');

        $this->call('GET', $this->backend_route . '/cp/dashboard');
        $this->assertRedirectedToRoute('larapress.home.login.get');
        $this->assertSessionHas('error', 'error');
    }

    public function test_can_access_on_given_permissions()
    {
        Permission::shouldReceive('has')->once()->andReturn(true);

        $this->call('GET', $this->backend_route . '/cp/dashboard');
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

        $this->call('GET', $this->backend_route . '/login');
        $this->assertResponseOk();
    }

    public function test_can_redirect_to_secure_urls_if_the_config_entry_is_set_to_true()
    {
        Config::set('larapress.settings.ssl', true);
        $request = $this->backend_route . '/login';
        $expected_redirect_url = url($request, array(), true);

        $this->call('GET', $this->backend_route . '/login');

        $this->assertRedirectedTo($expected_redirect_url);
    }

}
