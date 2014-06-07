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
	private $session;

	/**
	 * @var Mock
	 */
	private $redirect;

	public function setUp()
	{
		parent::setUp();

		$this->captcha = Mockery::mock('\Larapress\Interfaces\CaptchaInterface');
		$this->session = Mockery::mock('\Illuminate\Session\Store');
		$this->redirect = Mockery::mock('\Illuminate\Routing\Redirector');
	}

	public function tearDown()
	{
		parent::tearDown();

		Mockery::close();
	}

	protected function getForceHumanFilterInstance()
	{
		return new ForceHumanFilterProxy($this->captcha, $this->session, $this->redirect);
	}

	/**
	 * @test filter() can redirect back with a flash message if the captcha is required
	 */
	public function filter_can_redirect_back_with_a_flash_message_if_the_captcha_is_required()
	{
		$this->captcha->shouldReceive('isRequired')->withNoArgs()->once()->andReturn(true);
		$this->session->shouldReceive('flash')
			->with('error', 'Please verify that you are human first.')->once();
		$this->redirect->shouldReceive('back')->withNoArgs()->once()->andReturn('foo');
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
