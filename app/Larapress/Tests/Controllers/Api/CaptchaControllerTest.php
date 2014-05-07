<?php namespace Larapress\Tests\Controllers\Api;

use Larapress\Tests\TestCase;
use Mockably;
use Mockery;
use Session;
use Validator;

class CaptchaControllerTest extends TestCase
{

    private $success_respone;
    private $failed_response;
    private $captcha_session_key;

    public function setUp()
    {
        parent::setUp();

        $this->success_respone = '{"result":"success"}';
        $this->failed_response = '{"result":"failed"}';
        $this->captcha_session_key = 'captcha.passed.time';
    }

    /*
    |--------------------------------------------------------------------------
    | CaptchaController@postValidate Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the CaptchaController@postValidate method
    |
    */

    public function test_can_browse_the_captcha_validation_api_route()
    {
        $response = $this->route('POST', 'larapress.api.captcha.validate.post');

        $this->assertResponseOk();
    }

    public function test_can_validate_and_return_failed()
    {
        $validation_mock = Mockery::mock();
        $validation_mock->shouldReceive('fails')->once()->andReturn(true);

        Validator::shouldReceive('make')->once()->andReturn($validation_mock);

        $response = $this->route('POST', 'larapress.api.captcha.validate.post');

        $this->assertEquals($this->failed_response, $response->getContent());
    }

    public function test_can_get_a_success_json_response()
    {
        $validation_mock = Mockery::mock();
        $validation_mock->shouldReceive('fails')->once()->andReturn(false);

        Validator::shouldReceive('make')->once()->andReturn($validation_mock);

        $response = $this->route('POST', 'larapress.api.captcha.validate.post');

        $this->assertEquals($this->success_respone, $response->getContent());
    }

    public function test_can_write_the_current_microtime_into_the_session()
    {
        Mockably::shouldReceive('microtime')->once()->andReturn('foo');

        $this->test_can_get_a_success_json_response();

        $this->assertEquals('foo', Session::get($this->captcha_session_key));
    }

}
