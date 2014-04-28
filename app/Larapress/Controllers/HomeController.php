<?php namespace Larapress\Controllers;

use Cartalyst\Sentry\Throttling\UserBannedException;
use Cartalyst\Sentry\Throttling\UserSuspendedException;
use Cartalyst\Sentry\Users\LoginRequiredException;
use Cartalyst\Sentry\Users\PasswordRequiredException;
use Cartalyst\Sentry\Users\UserNotActivatedException;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Cartalyst\Sentry\Users\WrongPasswordException;
use Input;
use Redirect;
use Sentry;
use Session;
use View;

class HomeController extends BaseController
{

    public function getLogin()
    {
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

        // TODO Make this a named route!
        return Redirect::to('admin/cp/dashboard');
    }

}
