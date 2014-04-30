<?php namespace Larapress\Tests\Controllers;

use Larapress\Tests\TestCase;

class ControlPanelControllerTest extends TestCase
{
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
        $this->route('GET', 'larapress.cp.dashboard.get');

        $this->assertResponseOk();
    }

}
