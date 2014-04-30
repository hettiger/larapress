<?php namespace Larapress\Services;

use Cartalyst\Sentry\Users\UserNotFoundException;
use Config;
use Input;
use Lang;
use Larapress\Exceptions\MailException;
use Larapress\Interfaces\NarratorInterface;
use Mail;
use Sentry;
use Swift_TransportException;

class Narrator implements NarratorInterface
{

    /**
     * Send a simple email
     *
     * For more complex emails you might write another method
     *
     * @param array $to To details: 'address' and 'name'
     * @param string $subject The translated email subject
     * @param array|object $data The data you want to pass to the view
     * @param array|string $view The view you want to use (Further information can be found in the laravel docs)
     * @param string $mail_error_message The error message
     * @throws MailException Throws an exception containing further information as message
     * @return bool Returns true on success
     */
    public function sendMail($to, $subject, $data, $view, $mail_error_message)
    {
        $from = array(
            'address' => Config::get('larapress.email.from.address'),
            'name' => Config::get('larapress.email.from.name'),
        );

        try
        {
            $result = Mail::send($view, $data,
                function ($message) use ($from, $to, $subject) {
                    $message->from($from['address'], $from['name']);
                    $message->to($to['address'], $to['name'])->subject($subject);
                }
            );

            if ( ! $result ) {
                throw new MailException($mail_error_message);
            }

            return true;
        }
        catch (Swift_TransportException $e)
        {
            switch ( $e->getMessage() )
            {
                case 'Cannot send message without a sender address':
                    throw new MailException($e->getMessage());
                    break;
                default:
                    throw new MailException($mail_error_message);
            }
        }
    }

    /**
     * Request an account reset
     *
     * This will generate a reset password code for the given user and send it via email to him.
     *
     * @param Input|null $input Passing Input::all() can be omitted
     * @throws MailException Throws an exception containing further information as message
     * @throws UserNotFoundException Throws a UserNotFoundException if Sentry cannot find the given user.
     * @return bool Returns true on success
     */
    public function resetPassword($input = null)
    {
        $input = $input ? : Input::all();
        $user = Sentry::findUserByLogin($input['email']);
        $reset_code = $user->getResetPasswordCode();
        $cms_name = Config::get('larapress.names.cms');

        $to = array(
            'address' => $input['email'],
            'name' => $user['first_name'] . ' ' . $user['last_name'],
        );

        $subject = $cms_name . ' | ' . Lang::get('email.Password Reset!');

        $data = array(
            'cms_name' => $cms_name,
            'url' => route('larapress.home.send.new.password.get', array($reset_code)),
        );

        $view = array('text' => 'larapress.emails.reset-password');

        $mail_error_message = 'Sending the email containing the reset key failed. ' .
            'Please try again later or contact the administrator.';

        $this->sendMail($to, $subject, $data, $view, $mail_error_message);
    }

}
