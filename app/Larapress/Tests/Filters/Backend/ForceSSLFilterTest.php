<?php namespace Larapress\Tests\Filters;

use Larapress\Tests\TestCase;
use Route;
use Config;

class ForceSSLFilterTest extends TestCase
{
    private $backend_route;

    public function setUp()
    {
        parent::setUp();

        Route::enableFilters();

        $this->backend_route = Config::get('larapress.urls.backend');
    }

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
