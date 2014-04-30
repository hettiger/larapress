<?php namespace Larapress\Tests\Services;

use Larapress\Tests\TestCase;
use Log;
use Mail;
use Narrator;

class NarratorTest extends TestCase
{

    protected $log_message;

    /*
    |--------------------------------------------------------------------------
    | Helpers::sendMail() Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the Helpers::sendMail() method
    |
    */

    /**
     * @expectedException \Larapress\Exceptions\MailException
     * @expectedExceptionMessage foo
     */
    public function test_can_throw_a_mail_exception_with_error_message()
    {
        $to = array('address' => 'example@domain.com', 'name' => null);
        $subject = 'Test';
        $data = array();
        $view = array();
        $mail_error_message = 'foo';

        Mail::shouldReceive('send')->once()->andReturn(false);

        Narrator::sendMail($to, $subject, $data, $view, $mail_error_message);
    }

    /**
     * @expectedException \Larapress\Exceptions\MailException
     * @expectedExceptionMessage Cannot send message without a sender address
     */
    public function test_can_throw_a_mail_exception_warning_about_missing_sender_address()
    {
        $to = array('address' => 'example@domain.com', 'name' => null);
        $subject = 'Test';
        $data = array();
        $view = array();
        $mail_error_message = 'foo';

        Mail::shouldReceive('send')->once()->andThrow(
            'Swift_TransportException',
            'Cannot send message without a sender address'
        );

        Narrator::sendMail($to, $subject, $data, $view, $mail_error_message);
    }

    /**
     * @expectedException \Larapress\Exceptions\MailException
     * @expectedExceptionMessage foo
     */
    public function test_can_fall_back_to_the_provided_error_message_on_unknown_swift_transport_exception_messages()
    {
        $to = array('address' => 'example@domain.com', 'name' => null);
        $subject = 'Test';
        $data = array();
        $view = array();
        $mail_error_message = 'foo';

        Mail::shouldReceive('send')->once()->andThrow(
            'Swift_TransportException',
            'bar'
        );

        Narrator::sendMail($to, $subject, $data, $view, $mail_error_message);
    }

    public function test_can_send_an_email()
    {
        $to = array('address' => 'example@domain.com', 'name' => null);
        $subject = 'Test';
        $data = array();
        $view = array();
        $mail_error_message = '';

        Log::listen(function($level, $message, $context)
        {
            $this->log_message = $message;
        });

        Narrator::sendMail($to, $subject, $data, $view, $mail_error_message);
        $this->assertEquals('Pretending to mail message to: example@domain.com', $this->log_message);
    }

}
