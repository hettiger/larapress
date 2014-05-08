<?php namespace Larapress\Tests\Controllers\Api;

use Config;
use Helpers;
use Larapress\Controllers\Api\BaseController;
use Larapress\Tests\TestCase;
use Str;

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
    | BaseController@missingMethod Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the BaseController@missingMethod method
    |
    */

    public function test_can_catch_404_errors()
    {
        $this->call('GET', $this->backend_route . '/api/' . Str::quickRandom(16));

        $this->assertResponseStatus(404);
    }

    public function test_can_return_the_backend_404_response()
    {
        Helpers::shouldReceive('force404')->once();

        $controller = new BaseController;
        $controller->missingMethod(array());
    }

}
