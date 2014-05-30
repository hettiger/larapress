<?php namespace Larapress\Tests\Services;

use Mockery;
use Mockery\Mock;
use Larapress\Services\Narrator;
use PHPUnit_Framework_TestCase;

class NarratorTest extends PHPUnit_Framework_TestCase
{

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

    public function setUp()
    {
        parent::setUp();

        $this->config = Mockery::mock('\Illuminate\Config\Repository');
        $this->mail = Mockery::mock('\Illuminate\Mail\Mailer');
        $this->lang = Mockery::mock('\Illuminate\Translation\Translator');
        $this->input = Mockery::mock('\Illuminate\Http\Request');
        $this->sentry = Mockery::mock('\Cartalyst\Sentry\Sentry');
    }

    public function tearDown()
    {
        parent::tearDown();

        Mockery::close();
    }

    protected function getNarratorInstance()
    {
        return new Narrator($this->config, $this->mail, $this->lang, $this->input, $this->sentry);
    }

    protected function applyConfigFixture()
    {
        $this->config->shouldReceive('get')->with('larapress.email.from.address')->once();
        $this->config->shouldReceive('get')->with('larapress.email.from.name')->once();
        $this->config->shouldReceive('get')->with('larapress.names.cms')->once();
    }

    protected function getResetPasswordCodeMock()
    {
        $m = Mockery::mock();
        $m->shouldReceive('getResetPasswordCode')->once()->withNoArgs()->andReturn('foo');
        $m->shouldReceive('getAttribute')->withAnyArgs()->twice();
        $m->shouldReceive('getId')->withNoArgs()->once();

        return $m;
    }

    /*
    |--------------------------------------------------------------------------
    | Narrator::sendMail() Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the Narrator::sendMail() method
    |
    */

    /**
     * @expectedException \Larapress\Exceptions\MailException
     * @expectedExceptionMessage foo
     */
    public function test_can_throw_a_mail_exception_with_error_message()
    {
        $this->applyConfigFixture();
        $narrator = $this->getNarratorInstance();

        $narrator->setTo(array('address' => 'example@domain.com', 'name' => null));
        $narrator->setSubject('Test');
        $narrator->setData(array());
        $narrator->setView(array());
        $narrator->setMailErrorMessage('foo');

        $this->mail->shouldReceive('send')->once()->andReturn(false);

        $narrator->sendMail();
    }

    /**
     * @expectedException \Larapress\Exceptions\MailException
     * @expectedExceptionMessage Cannot send message without a sender address
     */
    public function test_can_throw_a_mail_exception_warning_about_missing_sender_address()
    {
        $this->applyConfigFixture();
        $narrator = $this->getNarratorInstance();

        $narrator->setTo(array('address' => 'example@domain.com', 'name' => null));
        $narrator->setSubject('Test');
        $narrator->setData(array());
        $narrator->setView(array());
        $narrator->setMailErrorMessage('foo');

        $this->mail->shouldReceive('send')->once()->andThrow(
            'Swift_TransportException',
            'Cannot send message without a sender address'
        );

        $narrator->sendMail();
    }

    /**
     * @expectedException \Larapress\Exceptions\MailException
     * @expectedExceptionMessage foo
     */
    public function test_can_fall_back_to_the_provided_error_message_on_unknown_swift_transport_exception_messages()
    {
        $this->applyConfigFixture();
        $narrator = $this->getNarratorInstance();

        $narrator->setTo(array('address' => 'example@domain.com', 'name' => null));
        $narrator->setSubject('Test');
        $narrator->setData(array());
        $narrator->setView(array());
        $narrator->setMailErrorMessage('foo');

        $this->mail->shouldReceive('send')->once()->andThrow(
            'Swift_TransportException',
            'bar'
        );

        $narrator->sendMail();
    }

    public function test_can_send_an_email()
    {
        $this->applyConfigFixture();
        $this->mail->shouldReceive('send')->once()->andReturn(true);
        $narrator = $this->getNarratorInstance();

        $narrator->setTo(array('address' => 'example@domain.com', 'name' => null));
        $narrator->setSubject('Test');
        $narrator->setData(array());
        $narrator->setView(array());
        $narrator->setMailErrorMessage('');

        $narrator->sendMail(); // No exception -> Success
    }

    /*
    |--------------------------------------------------------------------------
    | Narrator::resetPassword() Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the Narrator::resetPassword() method
    |
    */

    /**
     * @expectedException \Cartalyst\Sentry\Users\UserNotFoundException
     */
    public function test_can_throw_a_user_not_found_exception()
    {
        $this->applyConfigFixture();
        $this->input->shouldReceive('all')->withNoArgs()->andReturn(array('email' => 'example@domain.tld'));
        $this->sentry->shouldReceive('findUserByLogin')->with('example@domain.tld')
            ->andThrow('\Cartalyst\Sentry\Users\UserNotFoundException');

        $narrator = $this->getNarratorInstance();

        $narrator->resetPassword();
    }

    public function test_can_send_a_reset_password_email()
    {
        $this->lang->shouldReceive('get')->with('larapress::email.Password Reset!')->once();
        $this->applyConfigFixture();
        $this->input->shouldReceive('all')->withNoArgs()->andReturn(array('email' => 'example@domain.tld'));
        $this->mail->shouldReceive('send')->once()->andReturn(true);
        $this->sentry->shouldReceive('findUserByLogin')->with('example@domain.tld')->once()
            ->andReturn($this->getResetPasswordCodeMock());

        $narrator = $this->getNarratorInstance();

        $narrator->resetPassword(); // Would throw an exception on errors
    }

    /*
    |--------------------------------------------------------------------------
    | Narrator::sendNewPassword() Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the Narrator::sendNewPassword() method
    |
    */

    /**
     * @expectedException \Cartalyst\Sentry\Users\UserNotFoundException
     */
    public function test_can_throw_a_user_not_found_exception_when_trying_to_send_a_new_password()
    {
        $this->applyConfigFixture();
        $this->sentry->shouldReceive('findUserById')->with(2)->once()
            ->andThrow('\Cartalyst\Sentry\Users\UserNotFoundException');

        $narrator = $this->getNarratorInstance();

        $narrator->sendNewPassword(2, 'foo');
    }

    /**
     * @expectedException \Larapress\Exceptions\PasswordResetCodeInvalidException
     */
    public function test_can_throw_a_password_reset_code_invalid_exception()
    {
        $m = Mockery::mock();
        $m->shouldReceive('checkResetPasswordCode')->with('foo')->once()->andReturn(false);
        $m->shouldReceive('getAttribute')->withAnyArgs()->times(3);

        $this->applyConfigFixture();
        $this->lang->shouldReceive('get')->with('larapress::email.Password Reset!')->once();
        $this->sentry->shouldReceive('findUserById')->with(1)->once()->andReturn($m);

        $narrator = $this->getNarratorInstance();

        $narrator->sendNewPassword(1, 'foo');
    }

    public function test_can_send_the_new_password()
    {
        $user = Mockery::mock();
        $user->shouldReceive('checkResetPasswordCode')->with('foo')->once()->andReturn(true);
        $user->shouldReceive('getAttribute')->withAnyArgs()->times(3);
        $user->shouldReceive('getId')->withNoArgs()->once()->andReturn(1);
        $user->shouldReceive('attemptResetPassword')->withAnyArgs()->once()->andReturn(true);

        $throttle = Mockery::mock();
        $throttle->shouldReceive('unsuspend')->withNoArgs()->once()->andReturn(true);

        $this->applyConfigFixture();
        $this->lang->shouldReceive('get')->with('larapress::email.Password Reset!')->once();
        $this->sentry->shouldReceive('findUserById')->with(1)->once()->andReturn($user);
        $this->sentry->shouldReceive('findThrottlerByUserId')->with(1)->once()->andReturn($throttle);
        $this->mail->shouldReceive('send')->once()->andReturn(true);

        $narrator = $this->getNarratorInstance();

        $narrator->sendNewPassword(1, 'foo');
    }

}
