<?php namespace Larapress\Tests\Controllers;

use Config;
use Helpers;
use Larapress\Controllers\BaseController;
use Larapress\Tests\TestCase;
use Str;
use View;

class BaseControllerTest extends TestCase
{

    private $backend_route;

    public function setUp()
    {
        parent::setUp();

        $this->backend_route = Config::get('larapress.urls.backend');
    }

    /*
    |--------------------------------------------------------------------------
    | Constructor Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the constructor
    |
    */

    public function test_constructor_shares_important_data()
    {
        View::shouldReceive('share')->times(2);

        new BaseController;
    }

    /*
    |--------------------------------------------------------------------------
    | BaseController@missingMethod Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the BaseController@missingMethod method
    |
    */

    public function test_can_catch_404_errors()
    {
        $this->call('GET', $this->backend_route . '/' . Str::quickRandom(16));

        $this->assertResponseStatus(404);
    }

    public function test_can_set_the_correct_page_title()
    {
        Helpers::shouldReceive('setPageTitle')->with('404 Error')->once();
        View::share('title', 'foo'); // Prevent an for this test irrelevant ErrorException

        $controller = new BaseController;
        $controller->missingMethod(array());
    }

}
