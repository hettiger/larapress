<?php namespace Larapress\Tests\Controllers\Api;

use Larapress\Controllers\Api\CaptchaController;
use Mockery;
use Mockery\Mock;
use PHPUnit_Framework_TestCase;

class CaptchaControllerTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var Mock
	 */
	private $validator;

	/**
	 * @var Mock
	 */
	private $input;

	/**
	 * @var Mock
	 */
	private $response;

	/**
	 * @var Mock
	 */
	private $session;

	/**
	 * @var Mock
	 */
	private $mockably;

	public function setUp()
	{
		parent::setUp();

		$this->validator = Mockery::mock('\Illuminate\Validation\Factory');
		$this->input = Mockery::mock('\Illuminate\Http\Request');
		$this->response = Mockery::mock('\Illuminate\Support\Facades\Response');
		$this->session = Mockery::mock('\Illuminate\Session\Store');
		$this->mockably = Mockery::mock('\Larapress\Interfaces\MockablyInterface');
	}

	public function tearDown()
	{
		parent::tearDown();

		Mockery::close();
	}

	protected function getCaptchaControllerInstance()
	{
		return new CaptchaController($this->validator, $this->input, $this->response, $this->session, $this->mockably);
	}

	/**
	 * @param bool $return
	 * @return \Mockery\MockInterface
	 */
	protected function getFailsMockFixture($return)
	{
		$validator = Mockery::mock();
		$validator->shouldReceive('fails')->withNoArgs()->once()->andReturn($return);

		return $validator;
	}

	/**
	 * @test postValidate() does validate sets the timer and returns a success response
	 */
	public function postValidate_does_validate_sets_the_timer_and_returns_a_success_response()
	{
		$this->input->shouldReceive('all')->withNoArgs()->once()->andReturn(array('foo'));
		$this->validator->shouldReceive('make')
			->with(array('foo'), array('recaptcha_response_field' => 'required|recaptcha'))
			->once()->andReturn($this->getFailsMockFixture(false));
		$this->mockably->shouldReceive('microtime')->withNoArgs()->once()->andReturn(1.00);
		$this->session->shouldReceive('put')->with('captcha.passed.time', 1.00);
		$this->response->shouldReceive('json')->with(array('result' => 'success'))->once()->andReturn('bar');
		$controller = $this->getCaptchaControllerInstance();

		$this->assertEquals('bar', $controller->postValidate());
	}

	/**
	 * @test postValidate() returns a failed response on validation errors
	 */
	public function postValidate_returns_a_failed_response_on_validation_errors()
	{
		$this->input->shouldReceive('all')->withNoArgs()->once()->andReturn(array('foo'));
		$this->validator->shouldReceive('make')
			->with(array('foo'), array('recaptcha_response_field' => 'required|recaptcha'))
			->once()->andReturn($this->getFailsMockFixture(true));
		$this->response->shouldReceive('json')->with(array('result' => 'failed'))->once()->andReturn('bar');
		$controller = $this->getCaptchaControllerInstance();

		$this->assertEquals('bar', $controller->postValidate());
	}

}
