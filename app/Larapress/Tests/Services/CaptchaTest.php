<?php namespace Larapress\Tests\Services;

use Larapress\Services\Captcha;
use Mockery;
use Mockery\Mock;
use PHPUnit_Framework_TestCase;

class CaptchaTest extends PHPUnit_Framework_TestCase {

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

	/**
	 * @var Mock
	 */
	private $mockably;

	public function setUp()
	{
		parent::setUp();

		$this->view = Mockery::mock('Illuminate\View\Environment');
		$this->config = Mockery::mock('Illuminate\Config\Repository');
		$this->session = Mockery::mock('Illuminate\Session\Store');
		$this->helpers = Mockery::mock('Larapress\Services\Helpers');
		$this->mockably = Mockery::mock('Larapress\Services\Mockably');
	}

	public function tearDown()
	{
		parent::tearDown();

		Mockery::close();
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
		return new Captcha($this->view, $this->config, $this->session, $this->helpers, $this->mockably);
	}

	/**
	 * @test isRequired() always returns false if configured to be inactive
	 */
	public function isRequired_always_returns_false_if_configured_to_be_inactive()
	{
		$this->config->shouldReceive('get')->with('larapress.settings.captcha.active')->andReturn(false);
		$captcha = $this->getCaptchaInstance();

		$this->assertFalse($captcha->isRequired());
	}

	/**
	 * @test isRequired() returns true if the captcha did not exceed the timer limit
	 */
	public function isRequired_returns_true_if_the_captcha_did_exceed_the_timer_limit()
	{
		$this->applySessionFixture();
		$this->applyConfigFixture();
		$this->helpers->shouldReceive('getCurrentTimeDifference')->once()->andReturn(11);
		$captcha = $this->getCaptchaInstance();

		$this->assertTrue($captcha->isRequired());
	}

	/**
	 * @test isRequired() returns false if the captcha did exceed the timer limit
	 */
	public function isRequired_returns_false_if_the_captcha_did_not_exceed_the_timer_limit()
	{
		$this->applySessionFixture();
		$this->applyConfigFixture();
		$this->helpers->shouldReceive('getCurrentTimeDifference')->once()->andReturn(9);
		$captcha = $this->getCaptchaInstance();

		$this->assertFalse($captcha->isRequired());
	}

	/**
	 * @test isRequired() returns false if the captcha was passed in the same minute
	 */
	public function isRequired_returns_false_if_the_captcha_was_passed_in_the_same_minute()
	{
		$this->applySessionFixture();
		$this->applyConfigFixture();
		$this->helpers->shouldReceive('getCurrentTimeDifference')->once()->andReturn(0);
		$captcha = $this->getCaptchaInstance();

		$this->assertFalse($captcha->isRequired());
	}

	/**
	 * @test shareDataToViews() does actually share data to the views
	 */
	public function shareDataToViews_does_actually_share_data_to_the_views()
	{
		$this->mockably->shouldReceive('route')->with('larapress.api.captcha.validate.post')->once()->andReturn('url');
		$this->view->shouldReceive('share')->once()
			->with('captcha_validation_url', 'url');

		$captcha = $this->getCaptchaInstance();

		$captcha->shareDataToViews();
	}

}
