<?php namespace Larapress\Tests\Services;

use Larapress\Tests\Services\Proxies\NarratorProxy;
use Mockery;
use Mockery\Mock;
use PHPUnit_Framework_TestCase;

class NarratorTest extends PHPUnit_Framework_TestCase {

	public $log_message;

	/**
	 * @var Mock
	 */
	protected $config;

	/**
	 * @var Mock
	 */
	protected $mail;

	/**
	 * @var Mock
	 */
	protected $lang;

	/**
	 * @var Mock
	 */
	protected $input;

	/**
	 * @var Mock
	 */
	protected $sentry;

	/**
	 * @var Mock
	 */
	private $nullObject;

	/**
	 * @var Mock
	 */
	private $mockably;

	public function setUp()
	{
		parent::setUp();

		$this->config = Mockery::mock('\Illuminate\Config\Repository');
		$this->mail = Mockery::mock('\Illuminate\Mail\Mailer');
		$this->lang = Mockery::mock('\Illuminate\Translation\Translator');
		$this->input = Mockery::mock('\Illuminate\Http\Request');
		$this->sentry = Mockery::mock('\Cartalyst\Sentry\Sentry');
		$this->nullObject = Mockery::mock('\Larapress\Services\NullObject');
		$this->mockably = Mockery::mock('\Larapress\Services\Mockably');

		$this->nullObject->shouldDeferMissing();
	}

	public function tearDown()
	{
		parent::tearDown();

		Mockery::close();
	}

	protected function applyConfigFixture()
	{
		$this->config->shouldReceive('get')->with('larapress.email.from.address')->once();
		$this->config->shouldReceive('get')->with('larapress.email.from.name')->once();
		$this->config->shouldReceive('get')->with('larapress.names.cms')->once();
	}

	/**
	 * @param NarratorProxy $narrator
	 */
	protected function applyTestDataFixture($narrator)
	{
		$narrator->setTo(array('address' => 'example@domain.com', 'name' => 'foo'));
		$narrator->setSubject('bar');
		$narrator->setData(array());
		$narrator->setView('baz');
		$narrator->setMailErrorMessage('foo bar');
	}

	protected function getResetPasswordCodeMock()
	{
		$m = Mockery::mock();
		$m->shouldReceive('getResetPasswordCode')->once()->withNoArgs()->andReturn('foo');
		$m->shouldReceive('getAttribute')->withAnyArgs()->times(3);
		$m->shouldReceive('getId')->withNoArgs()->once();

		return $m;
	}

	protected function getNarratorInstance()
	{
		return new NarratorProxy(
			$this->config,
			$this->mail,
			$this->lang,
			$this->input,
			$this->sentry,
			$this->nullObject,
			$this->mockably
		);
	}

	/**
	 * @test init(), setCmsName() and setFrom()
	 */
	public function init()
	{
		$this->config->shouldReceive('get')->with('larapress.email.from.address')->atLeast()->once()->andReturn('foo');
		$this->config->shouldReceive('get')->with('larapress.email.from.name')->atLeast()->once()->andReturn('bar');
		$this->config->shouldReceive('get')->with('larapress.names.cms')->atLeast()->once()->andReturn('baz');
		$this->nullObject->shouldReceive('validateMailRecipientData')
			->with(array('address' => 'foo', 'name' => 'bar'))->atLeast()->once();

		$narrator = $this->getNarratorInstance();
		$narrator->init();

		$this->assertEquals(array('address' => 'foo', 'name' => 'bar'), $narrator->getFrom());
		$this->assertEquals('baz', $narrator->getCmsName());
	}

	/**
	 * @test setData()
	 */
	public function setData()
	{
		$this->applyConfigFixture();
		$this->nullObject->shouldReceive('validateVarIsArray')->with(array('foo' => 'bar'))->once();
		$narrator = $this->getNarratorInstance();

		$narrator->setData(array('foo' => 'bar'));

		$this->assertEquals(array('foo' => 'bar'), $narrator->getData());
	}

	/**
	 * @test setMailErrorMessage()
	 */
	public function setMailErrorMessage()
	{
		$this->applyConfigFixture();
		$this->nullObject->shouldReceive('validateVarIsString')->with('foo')->once();
		$narrator = $this->getNarratorInstance();

		$narrator->setMailErrorMessage('foo');

		$this->assertEquals('foo', $narrator->getMailErrorMessage());
	}

	/**
	 * @test setSubject()
	 */
	public function setSubject()
	{
		$this->applyConfigFixture();
		$this->nullObject->shouldReceive('validateVarIsString')->with('foo')->once();
		$narrator = $this->getNarratorInstance();

		$narrator->setSubject('foo');

		$this->assertEquals('foo', $narrator->getSubject());
	}

	/**
	 * @test setTo()
	 */
	public function setTo()
	{
		$this->applyConfigFixture();
		$this->nullObject->shouldReceive('validateMailRecipientData')
			->with(array('address' => 'baz', 'name' => 'foo bar'))->atLeast()->once();
		$narrator = $this->getNarratorInstance();

		$narrator->setTo(array('address' => 'baz', 'name' => 'foo bar'));

		$this->assertEquals(array('address' => 'baz', 'name' => 'foo bar'), $narrator->getTo());
	}

	/**
	 * @test setView()
	 */
	public function setView()
	{
		$this->applyConfigFixture();
		$this->nullObject->shouldReceive('validateMailViewDetails')->with('foo')->once();
		$narrator = $this->getNarratorInstance();

		$narrator->setView('foo');

		$this->assertEquals('foo', $narrator->getView());
	}

	/**
	 * @test handleTransportExceptions() can throw with special error message
	 * @expectedException \Larapress\Exceptions\MailException
	 * @expectedExceptionMessage Cannot send message without a sender address
	 */
	public function handleTransportExceptions_can_throw_with_special_error_message()
	{
		$this->applyConfigFixture();
		$narrator = $this->getNarratorInstance();
		$special_message = 'Cannot send message without a sender address';

		$e = Mockery::mock();
		$e->shouldReceive('getMessage')->withNoArgs()->atLeast()->once()->andReturn($special_message);
		$narrator->handleTransportExceptions($e);
	}

	/**
	 * @test handleTransportExceptions() can throw the predefined mail error message
	 * @expectedException \Larapress\Exceptions\MailException
	 * @expectedExceptionMessage bar
	 */
	public function handleTransportExceptions_can_throw_the_predefined_mail_error_message()
	{
		$this->applyConfigFixture();
		$narrator = $this->getNarratorInstance();
		$input_message = 'foo';

		$e = Mockery::mock();
		$e->shouldReceive('getMessage')->withNoArgs()->atLeast()->once()->andReturn($input_message);
		$narrator->setMailErrorMessage('bar');
		$narrator->handleTransportExceptions($e);
	}

	/**
	 * @test sendMail() can throw the predefined mail exception on false result
	 * @expectedException \Larapress\Exceptions\MailException
	 * @expectedExceptionMessage foo bar
	 */
	public function sendMail_can_throw_the_predefined_mail_exception_on_false_result()
	{
		$this->applyConfigFixture();
		$this->mail->shouldReceive('send')->once()->andReturn(false);
		$narrator = $this->getNarratorInstance();
		$this->applyTestDataFixture($narrator);

		$narrator->sendMail();
	}

	/**
	 * @test sendMail() can handle transport exceptions
	 * @expectedException \Larapress\Exceptions\MailException
	 * @expectedExceptionMessage foo bar
	 */
	public function sendMail_can_handle_transport_exceptions()
	{
		$this->applyConfigFixture();
		$this->mail->shouldReceive('send')->once()->andThrow('Swift_TransportException', 'baz');
		$narrator = $this->getNarratorInstance();
		$this->applyTestDataFixture($narrator);

		$narrator->sendMail();
	}

	/**
	 * @test sendMail() remains silent on success
	 */
	public function sendMail_remains_silent_on_success()
	{
		$this->applyConfigFixture();
		$this->mail->shouldReceive('send')->once()->andReturn(true);
		$narrator = $this->getNarratorInstance();
		$this->applyTestDataFixture($narrator);

		$narrator->sendMail();
	}

	/**
	 * @test prepareResetRequestMailData() can set the data
	 */
	public function prepareResetRequestMailData_can_set_the_data()
	{
		$this->applyConfigFixture();
		$narrator = $this->getNarratorInstance();
		$user_mock = Mockery::mock();
		$user_mock->shouldReceive('getAttribute')->with('email')->once()->andReturn('baz');
		$user_mock->shouldReceive('getAttribute')->with('first_name')->once()->andReturn('John');
		$user_mock->shouldReceive('getAttribute')->with('last_name')->once()->andReturn('Doe');
		$user_mock->shouldReceive('getId')->withNoArgs()->once()->andReturn(1);
		$this->lang->shouldReceive('get')->with('larapress::email.Password Reset!')->once()->andReturn('Subject');
		$this->mockably->shouldReceive('route')->with('larapress.home.send.new.password.get', array(1, 'bar'))
			->once()->andReturn('url');

		$narrator->prepareResetRequestMailData($user_mock, 'bar');

		$this->assertEquals(array('address' => 'baz', 'name' => 'John Doe'), $narrator->getTo());
		$this->assertEquals(' | Subject', $narrator->getSubject());
		$this->assertEquals(array(
			'cms_name' => null,
			'url' => 'url'
		), $narrator->getData());
		$this->assertEquals(array('text' => 'larapress::emails.reset-password'), $narrator->getView());
	}

	/**
	 * @test resetPassword()
	 */
	public function resetPassword()
	{
		$this->applyConfigFixture();
		$this->input->shouldReceive('all')->once()->andReturn(array('email' => 'baz'));
		$this->sentry->shouldReceive('findUserByLogin')->once()->andReturn($this->getResetPasswordCodeMock());
		$this->lang->shouldReceive('get')->withAnyArgs()->once()->andReturn('Subject');
		$this->mail->shouldReceive('send')->once()->andReturn(true);
		$this->mockably->shouldReceive('route')->once();
		$narrator = $this->getNarratorInstance();

		$narrator->resetPassword();

		$this->assertEquals('Sending the email containing the reset key failed. ' .
			'Please try again later or contact the administrator.', $narrator->getMailErrorMessage());
	}

	/**
	 * @test unsuspendUserAndResetPassword() can return a new password and unsuspend
	 */
	public function unsuspendUserAndResetPassword_can_return_a_new_password_and_unsuspend()
	{
		$user = Mockery::mock();
		$user->shouldReceive('getId')->withNoArgs()->once()->andReturn(1);
		$user->shouldReceive('attemptResetPassword')->with('foo', 'bar')->once()->andReturn(true);

		$throttle = Mockery::mock();
		$throttle->shouldReceive('unsuspend')->withNoArgs()->once()->andReturn(true);

		$this->applyConfigFixture();
		$this->sentry->shouldReceive('findThrottlerByUserId')->with(1)->once()->andReturn($throttle);
		$this->mockably->shouldReceive('str_random')->with(16)->once()->andReturn('bar');
		$narrator = $this->getNarratorInstance();

		$this->assertEquals('bar', $narrator->unsuspendUserAndResetPassword($user, 'foo'));
	}

	/**
	 * @test unsuspendUserAndResetPassword() can throw an exception on failure
	 * @expectedException \Larapress\Exceptions\PasswordResetFailedException
	 */
	public function unsuspendUserAndResetPassword_can_throw_an_exception_on_failure()
	{
		$user = Mockery::mock();
		$user->shouldReceive('getId')->withNoArgs()->once()->andReturn(1);
		$user->shouldReceive('attemptResetPassword')->with('foo', 'bar')->once()->andReturn(false);

		$throttle = Mockery::mock();
		$throttle->shouldReceive('unsuspend')->withNoArgs()->once()->andReturn(true);

		$this->applyConfigFixture();
		$this->sentry->shouldReceive('findThrottlerByUserId')->with(1)->once()->andReturn($throttle);
		$this->mockably->shouldReceive('str_random')->with(16)->once()->andReturn('bar');
		$narrator = $this->getNarratorInstance();

		$narrator->unsuspendUserAndResetPassword($user, 'foo');
	}

	/**
	 * @test attemptToReset() can throw a password reset code invalid exception
	 * @expectedException \Larapress\Exceptions\PasswordResetCodeInvalidException
	 */
	public function attemptToReset_can_throw_a_password_reset_code_invalid_exception()
	{
		$user = Mockery::mock();
		$user->shouldReceive('checkResetPasswordCode')->with('foo')->once()->andReturn(false);

		$this->applyConfigFixture();
		$narrator = $this->getNarratorInstance();

		$narrator->attemptToReset($user, 'foo');
	}

	/**
	 * @test attemptToReset() can return the new password on success
	 */
	public function attemptToReset_can_return_the_new_password_on_success()
	{
		$user = Mockery::mock();
		$user->shouldReceive('checkResetPasswordCode')->with('foo')->once()->andReturn(true);
		$user->shouldReceive('getId')->withNoArgs()->once()->andReturn(1);
		$user->shouldReceive('attemptResetPassword')->with('foo', 'bar')->once()->andReturn(true);

		$throttle = Mockery::mock();
		$throttle->shouldReceive('unsuspend')->withNoArgs()->once()->andReturn(true);

		$this->applyConfigFixture();
		$this->sentry->shouldReceive('findThrottlerByUserId')->with(1)->once()->andReturn($throttle);
		$this->mockably->shouldReceive('str_random')->with(16)->once()->andReturn('bar');
		$narrator = $this->getNarratorInstance();

		$this->assertEquals('bar', $narrator->attemptToReset($user, 'foo'));
	}

	/**
	 * @test prepareResetResultMailData() can set the data
	 */
	public function prepareResetResultMailData_can_set_the_data()
	{
		$user = Mockery::mock();
		$user->shouldReceive('getAttribute')->with('email')->once()->andReturn('baz');
		$user->shouldReceive('getAttribute')->with('first_name')->once()->andReturn('John');
		$user->shouldReceive('getAttribute')->with('last_name')->once()->andReturn('Doe');

		$throttle = Mockery::mock();
		$throttle->shouldReceive('unsuspend')->withNoArgs()->once()->andReturn(true);

		$this->applyConfigFixture();
		$this->lang->shouldReceive('get')->with('larapress::email.Password Reset!')->once()->andReturn('Subject');
		$user->shouldReceive('checkResetPasswordCode')->with('foo')->once()->andReturn(true);
		$user->shouldReceive('getId')->withNoArgs()->once()->andReturn(1);
		$this->sentry->shouldReceive('findThrottlerByUserId')->with(1)->once()->andReturn($throttle);
		$user->shouldReceive('attemptResetPassword')->with('foo', 'bar')->once()->andReturn(true);
		$this->mockably->shouldReceive('str_random')->with(16)->once()->andReturn('bar');
		$narrator = $this->getNarratorInstance();

		$narrator->prepareResetResultMailData($user, 'foo');

		$this->assertEquals(array('address' => 'baz', 'name' => 'John Doe'), $narrator->getTo());
		$this->assertEquals(' | Subject', $narrator->getSubject());
		$this->assertEquals(array('new_password' => 'bar'), $narrator->getData());
		$this->assertEquals(array('text' => 'larapress::emails.new-password'), $narrator->getView());
	}

	/**
	 * @test sendNewPassword() can send a new password
	 */
	public function sendNewPassword_can_send_a_new_password()
	{
		$user = Mockery::mock();
		$user->shouldReceive('getAttribute')->with('email')->once()->andReturn('baz');
		$user->shouldReceive('getAttribute')->with('first_name')->once()->andReturn('John');
		$user->shouldReceive('getAttribute')->with('last_name')->once()->andReturn('Doe');
		$user->shouldReceive('checkResetPasswordCode')->with('foo')->once()->andReturn(true);
		$user->shouldReceive('getId')->withNoArgs()->once()->andReturn(1);
		$user->shouldReceive('attemptResetPassword')->with('foo', 'bar')->once()->andReturn(true);

		$throttle = Mockery::mock();
		$throttle->shouldReceive('unsuspend')->withNoArgs()->once()->andReturn(true);

		$this->sentry->shouldDeferMissing();
		$this->sentry->shouldReceive('findUserById')->with(1)->once()->andReturn($user);
		$this->mockably->shouldReceive('str_random')->with(16)->once()->andReturn('bar');
		$this->sentry->shouldReceive('findThrottlerByUserId')->with(1)->once()->andReturn($throttle);
		$this->applyConfigFixture();
		$this->lang->shouldReceive('get')->with('larapress::email.Password Reset!')->once()->andReturn('Subject');
		$this->mail->shouldReceive('send')->once()->andReturn(true);
		$narrator = $this->getNarratorInstance();

		$narrator->sendNewPassword(1, 'foo');

		$this->assertEquals('Sending the email containing the new password failed. ' .
			'Please try again later or contact the administrator.', $narrator->getMailErrorMessage());
	}

}
