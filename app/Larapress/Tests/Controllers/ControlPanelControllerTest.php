<?php namespace Larapress\Tests\Controllers;

use Config;
use Larapress\Tests\TestCase;

class ControlPanelControllerTest extends TestCase
{
    private $backend_route;

    public function setUp()
    {
        parent::setUp();

        $this->backend_route = Config::get('larapress.urls.backend');
    }

    /*
    |--------------------------------------------------------------------------
    | ControlPanelController@getDashboard Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the ControlPanelController@getDashboard method
    |
    */

    public function test_can_browse_the_dashboard()
    {
        $this->call('GET', $this->backend_route . '/cp/dashboard');

        $this->assertResponseOk();
    }

}
