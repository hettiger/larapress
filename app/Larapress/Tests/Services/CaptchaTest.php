<?php namespace Larapress\Tests\Services;

use Larapress\Services\Captcha;
use Mockery;
use Mockery\Mock;
use PHPUnit_Framework_TestCase;

class CaptchaTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Mock
     */
    protected $view;

    /**
     * @var Mock
     */
    protected $config;

    /**
     * @var Mock
     */
    protected $session;

    /**
     * @var Mock
     */
    protected $helpers;

    public function setUp()
    {
        parent::setUp();

        $this->view = Mockery::mock('Illuminate\View\Environment');
        $this->config = Mockery::mock('Illuminate\Config\Repository');
        $this->session = Mockery::mock('Illuminate\Session\Store');
        $this->helpers = Mockery::mock('Larapress\Services\Helpers');
    }

    protected function applyConfigFixture()
    {
        $this->config->shouldReceive('get')->with('larapress.settings.captcha.active')->andReturn(true);
        $this->config->shouldReceive('get')->with('larapress.settings.captcha.timer')->andReturn(10);
    }

    protected function applySessionFixture()
    {
        $this->session->shouldReceive('get')->with('captcha.passed.time', 0)->once();
    }

    protected function getCaptchaInstance()
    {
        return new Captcha($this->view, $this->config, $this->session, $this->helpers);
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
        $this->config->shouldReceive('get')->with('larapress.settings.captcha.active')->andReturn(false);
        $captcha = $this->getCaptchaInstance();

        $this->assertFalse($captcha->isRequired());
    }

    public function test_can_return_true_if_passed_captcha_more_than_10_minutes_before()
    {
        $this->applySessionFixture();
        $this->applyConfigFixture();
        $this->helpers->shouldReceive('getCurrentTimeDifference')->once()->andReturn(11);
        $captcha = $this->getCaptchaInstance();

        $this->assertTrue($captcha->isRequired());
    }

    public function test_can_return_false_if_passed_captcha_less_than_10_minutes_before()
    {
        $this->applySessionFixture();
        $this->applyConfigFixture();
        $this->helpers->shouldReceive('getCurrentTimeDifference')->once()->andReturn(9);
        $captcha = $this->getCaptchaInstance();

        $this->assertFalse($captcha->isRequired());
    }

    public function test_can_return_true_if_passed_captcha_exactly_10_minutes_before()
    {
        $this->applySessionFixture();
        $this->applyConfigFixture();
        $this->helpers->shouldReceive('getCurrentTimeDifference')->once()->andReturn(10);
        $captcha = $this->getCaptchaInstance();

        $this->assertTrue($captcha->isRequired());
    }

    public function test_can_return_false_if_passed_captcha_exactly_0_minutes_before()
    {
        $this->applySessionFixture();
        $this->applyConfigFixture();
        $this->helpers->shouldReceive('getCurrentTimeDifference')->once()->andReturn(0);
        $captcha = $this->getCaptchaInstance();

        $this->assertFalse($captcha->isRequired());
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
        $this->view->shouldReceive('share')->once()
            ->with('captcha_validation_url', route('larapress.api.captcha.validate.post'));

        $captcha = $this->getCaptchaInstance();

        $captcha->shareDataToViews();
    }

}
