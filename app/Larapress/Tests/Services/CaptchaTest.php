<?php namespace Larapress\Tests\Services;

use Captcha;
use Config;
use Helpers;
use Larapress\Tests\TestCase;
use View;

class CaptchaTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();

        Config::set('larapress.settings.captcha.active', true);
        Config::set('larapress.settings.captcha.timer', 10);
    }

    /*
    |--------------------------------------------------------------------------
    | Captcha::isRequired() Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the Captcha::isRequired() method
    |
    */

    public function test_can_return_false_if_deactivated_by_config()
    {
        Config::set('larapress.settings.captcha.active', false);

        $this->assertFalse(Captcha::isRequired());
    }

    public function test_can_return_true_if_passed_captcha_more_than_10_minutes_before()
    {
        Helpers::shouldReceive('getCurrentTimeDifference')->once()->andReturn(11);

        $this->assertTrue(Captcha::isRequired());
    }

    public function test_can_return_false_if_passed_captcha_less_than_10_minutes_before()
    {
        Helpers::shouldReceive('getCurrentTimeDifference')->once()->andReturn(9);

        $this->assertFalse(Captcha::isRequired());
    }

    public function test_can_return_true_if_passed_captcha_exactly_10_minutes_before()
    {
        Helpers::shouldReceive('getCurrentTimeDifference')->once()->andReturn(10);

        $this->assertTrue(Captcha::isRequired());
    }

    public function test_can_return_false_if_passed_captcha_exactly_0_minutes_before()
    {
        Helpers::shouldReceive('getCurrentTimeDifference')->once()->andReturn(0);

        $this->assertFalse(Captcha::isRequired());
    }

    /*
    |--------------------------------------------------------------------------
    | Captcha::shareDataToViews() Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the Captcha::shareDataToViews() method
    |
    */

    public function test_does_share_data_to_the_views()
    {
        View::shouldReceive('share')->once();

        Captcha::shareDataToViews();
    }

}
