<?php namespace Larapress\Controllers;

use App;
use Cartalyst\Sentry\Throttling\UserBannedException;
use Cartalyst\Sentry\Throttling\UserSuspendedException;
use Cartalyst\Sentry\Users\LoginRequiredException;
use Cartalyst\Sentry\Users\PasswordRequiredException;
use Cartalyst\Sentry\Users\UserNotActivatedException;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Cartalyst\Sentry\Users\WrongPasswordException;
use Helpers;
use Input;
use Larapress\Exceptions\MailException;
use Larapress\Exceptions\PasswordResetCodeInvalidException;
use Larapress\Exceptions\PasswordResetFailedException;
use Larapress\Exceptions\PermissionMissingException;
use Narrator;
use Permission;
use Redirect;
use Sentry;
use Session;
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
        try
        {
            Narrator::resetPassword();
        }
        catch (UserNotFoundException $e)
        {
            Session::flash('error', 'User was not found.');
            return Redirect::route('larapress.home.reset.password.get')->withInput(Input::all());
        }
        catch (MailException $e)
        {
            Session::flash('error', $e->getMessage());
            return Redirect::route('larapress.home.reset.password.get')->withInput(Input::all());
        }

        Session::flash('success', 'Now please check your email account for further instructions!');
        return Redirect::route('larapress.home.reset.password.get');
    }

    public function getSendNewPassword($id = null, $reset_code = null)
    {
        try
        {
            Narrator::sendNewPassword($id, $reset_code);
        }
        catch (UserNotFoundException $e)
        {
            return Helpers::force404();
        }
        catch (PasswordResetFailedException $e)
        {
            Session::flash('error', 'Resetting your password failed. ' .
                'Please try again later or contact the administrator.');

            return Redirect::route('larapress.home.reset.password.get');
        }
        catch (PasswordResetCodeInvalidException $e)
        {
            return Helpers::force404();
        }
        catch (MailException $e)
        {
            Session::flash('error', $e->getMessage());
            return Redirect::route('larapress.home.reset.password.get');
        }

        Session::flash('success', 'Now please check your email account for the new password!');
        return Redirect::route('larapress.home.login.get');
    }

}
