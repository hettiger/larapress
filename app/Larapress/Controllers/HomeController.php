<?php namespace Larapress\Controllers;

use Cartalyst\Sentry\Throttling\UserBannedException;
use Cartalyst\Sentry\Throttling\UserSuspendedException;
use Cartalyst\Sentry\Users\LoginRequiredException;
use Cartalyst\Sentry\Users\PasswordRequiredException;
use Cartalyst\Sentry\Users\UserNotActivatedException;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Cartalyst\Sentry\Users\WrongPasswordException;
use Config;
use Helpers;
use Input;
use Larapress\Exceptions\PermissionMissingException;
use Mail;
use Permission;
use Redirect;
use Sentry;
use Session;
use Swift_TransportException;
use View;

class HomeController extends BaseController
{

    public function getIndex()
    {
        try
        {
            Permission::has('access.backend');
        }
        catch (PermissionMissingException $e)
        {
            return Redirect::route('larapress.home.login.get');
        }

        return Redirect::route('larapress.cp.dashboard.get');
    }

    public function getLogin()
    {
        Helpers::setPageTitle('Login');

        return View::make('larapress.pages.home.login');
    }

    public function postLogin()
    {
        $input = Input::all();

        try
        {
            $credentials = array(
                'email'    => $input['email'],
                'password' => $input['password'],
            );

            Sentry::authenticate($credentials, false);
        }
        catch (LoginRequiredException $e)
        {
            Session::flash('error', 'Login field is required.');
            return Redirect::route('larapress.home.login.get')->withInput(Input::except('password'));
        }
        catch (PasswordRequiredException $e)
        {
            Session::flash('error', 'Password field is required.');
            return Redirect::route('larapress.home.login.get')->withInput(Input::except('password'));
        }
        catch (WrongPasswordException $e)
        {
            Session::flash('error', 'Wrong password, try again.');
            return Redirect::route('larapress.home.login.get')->withInput(Input::except('password'));
        }
        catch (UserNotFoundException $e)
        {
            Session::flash('error', 'User was not found.');
            return Redirect::route('larapress.home.login.get')->withInput(Input::except('password'));
        }
        catch (UserNotActivatedException $e)
        {
            Session::flash('error', 'User is not activated.');
            return Redirect::route('larapress.home.login.get')->withInput(Input::except('password'));
        }
        catch (UserSuspendedException $e)
        {
            Session::flash('error', 'User is suspended.');
            return Redirect::route('larapress.home.login.get')->withInput(Input::except('password'));
        }
        catch (UserBannedException $e)
        {
            Session::flash('error', 'User is banned.');
            return Redirect::route('larapress.home.login.get')->withInput(Input::except('password'));
        }

        return Redirect::route('larapress.cp.dashboard.get');
    }

    public function getLogout()
    {
        if ( Sentry::check() )
        {
            Sentry::logout();
            Session::flash('success', 'You have successfully logged out.');
        }

        return Redirect::route('larapress.home.login.get');
    }

    public function getResetPassword()
    {
        Helpers::setPageTitle('Reset Password');

        return View::make('larapress.pages.home.reset-password');
    }

    public function postResetPassword()
    {
        $input = Input::all();

        try
        {
            /*
             * TODO Start refactoring here!
             * TODO Write some new content for the reset password email including translations
             */
            $user = Sentry::findUserByLogin($input['email']);
            $resetCode = $user->getResetPasswordCode();
            $cms_name = Config::get('larapress.names.cms');
            $url = route('larapress.home.skip.password.get', array($resetCode));

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

            Mail::queue(
                array('text' => 'larapress.emails.reset-password'),
                $data,
                function ($message) use ($from, $to) {
                    $message->from($from['address'], $from['name']);
                    $message->to($to['address'], $to['name'])->subject('Password Reset!');
                }
            );
            // TODO Stop refactoring here

            Session::flash('success', 'Now please check your email account for further instructions!');
            return Redirect::route('larapress.home.reset.password.get');
        }
        catch (UserNotFoundException $e)
        {
            Session::flash('error', 'User was not found.');
            return Redirect::route('larapress.home.reset.password.get')->withInput(Input::all());
        }
        catch (Swift_TransportException $e)
        {
            Session::flash('error', $e->getMessage());
            return Redirect::route('larapress.home.reset.password.get')->withInput(Input::all());
        }
    }

    public function getSendNewPassword()
    {
        // TODO Have a look at following return statement

        return 'TODO: Add the new password sending functionality ;-)';
    }

}
