<?php namespace Larapress\Interfaces;

use Cartalyst\Sentry\Users\UserNotFoundException;
use Larapress\Exceptions\MailException;

interface NarratorInterface {

    /**
     * Request an account reset
     *
     * This will generate a reset password code for the given user and send it via email to him.
     *
     * @param array $input Input::all() of the reset password form
     * @throws MailException Throws an exception containing further information as message
     * @throws UserNotFoundException Throws a UserNotFoundException if Sentry cannot find the given user.
     * @return bool Returns true on success
     */
    public function resetPassword($input);

}
