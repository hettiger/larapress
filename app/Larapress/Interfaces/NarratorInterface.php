<?php namespace Larapress\Interfaces;

use Cartalyst\Sentry\Users\UserNotFoundException;
use Input;
use Larapress\Exceptions\MailException;

interface NarratorInterface {

    /**
     * Send a simple email
     *
     * For more complex emails you might write another method
     *
     * @param array $from From details: 'address' and 'name'
     * @param array $to To details: 'address' and 'name'
     * @param string $subject The translated email subject
     * @param array|object $data The data you want to pass to the view
     * @param array|string $view The view you want to use (Further information can be found in the laravel docs)
     * @param string $mail_error_message The error message
     * @throws MailException Throws an exception containing further information as message
     * @return bool Returns true on success
     */
    public function sendMail($from, $to, $subject, $data, $view, $mail_error_message);

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
    public function resetPassword($input);

}
