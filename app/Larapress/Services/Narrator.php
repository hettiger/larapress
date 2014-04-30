<?php namespace Larapress\Services;

use Cartalyst\Sentry\Users\UserNotFoundException;
use Config;
use Input;
use Larapress\Exceptions\MailException;
use Larapress\Interfaces\NarratorInterface;
use Mail;
use Sentry;
use Swift_TransportException;

class Narrator implements NarratorInterface
{

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
        $resetCode = $user->getResetPasswordCode();
        $cms_name = Config::get('larapress.names.cms');
        $url = route('larapress.home.send.new.password.get', array($resetCode));

        $from = array(
            'address' => Config::get('larapress.email.from.address'),
            'name' => Config::get('larapress.email.from.name'),
        );

        $to = array(
            'address' => $input['email'],
            'name' => $user['first_name'] . ' ' . $user['last_name'],
        );

        $data = array(
            'cms_name' => $cms_name,
            'url' => $url,
        );

        try
        {
            $result = Mail::send(
                array('text' => 'larapress.emails.reset-password'),
                $data,
                function ($message) use ($from, $to) {
                    $message->from($from['address'], $from['name']);
                    $message->to($to['address'], $to['name'])->subject('Password Reset!');
                }
            );

            if ( ! $result ) {
                throw new MailException(
                    'Sending the email containing the reset key failed. ' .
                    'Please try again later or contact the administrator.'
                );
            }

            return true;
        }
        catch (Swift_TransportException $e)
        {
            throw new MailException($e->getMessage());
        }
    }

}
