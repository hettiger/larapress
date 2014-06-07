<?php namespace Larapress\Tests\Filters\Special;

use Larapress\Tests\Filters\Special\Proxies\ForceHumanFilterProxy;
use Mockery\Mock;
use Mockery;
use PHPUnit_Framework_TestCase;

class ForceHumanFilterTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var Mock
	 */
	private $captcha;

	/**
	 * @var Mock
	 */
	private $helpers;

	public function setUp()
	{
		parent::setUp();

		$this->captcha = Mockery::mock('\Larapress\Interfaces\CaptchaInterface');
		$this->helpers = Mockery::mock('\Larapress\Interfaces\HelpersInterface');
	}

	public function tearDown()
	{
		parent::tearDown();

		Mockery::close();
	}

	protected function getForceHumanFilterInstance()
	{
		return new ForceHumanFilterProxy($this->captcha, $this->helpers);
	}

	/**
	 * @test filter() can redirect back with a flash message if the captcha is required
	 */
	public function filter_can_redirect_back_with_a_flash_message_if_the_captcha_is_required()
	{
		$this->captcha->shouldReceive('isRequired')->withNoArgs()->once()->andReturn(true);
		$this->helpers->shouldReceive('redirectWithFlashMessage')
			->with('error', 'Please verify that you are human first.')->once()->andReturn('foo');
		$filter = $this->getForceHumanFilterInstance();

		$this->assertEquals('foo', $filter->filter());
	}

	/**
	 * @test filter() returns null if the captcha is not required
	 */
	public function filter_returns_null_if_the_captcha_is_not_required()
	{
		$this->captcha->shouldReceive('isRequired')->withNoArgs()->once()->andReturn(false);
		$filter = $this->getForceHumanFilterInstance();

		$this->assertNull($filter->filter());
	}

}
