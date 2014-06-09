<?php namespace Larapress\Controllers\Backend;

use Captcha;
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
use Response;
use Sentry;
use Session;
use View;

class HomeController extends BackendBaseController {

	/**
	 * Index
	 *
	 * Redirect a user depending on his permissions when he browses the backend route.
	 *
	 * @return Redirect
	 */
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

	/**
	 * Login
	 *
	 * Shows the login form to the world!
	 *
	 * @return View
	 */
	public function getLogin()
	{
		Helpers::setPageTitle('Login');

		return View::make('larapress::pages.home.login');
	}

	/**
	 * Redirect to the login route with a flash message keeping the input except for the password
	 *
	 * @param string $message The error message
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function loginRedirectFixture($message)
	{
		return Helpers::redirectWithFlashMessage('error', $message, 'larapress.home.login.get')
			->withInput(Input::except('password'));
	}

	/**
	 * Login
	 *
	 * This method will be processed once you try to login.
	 * It redirects you either back to the login page with an error message or to the dashboard.
	 *
	 * @return Redirect
	 */
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
			return $this->loginRedirectFixture('Login field is required.');
		}
		catch (PasswordRequiredException $e)
		{
			return $this->loginRedirectFixture('Password field is required.');
		}
		catch (WrongPasswordException $e)
		{
			return $this->loginRedirectFixture('Wrong password, try again.');
		}
		catch (UserNotFoundException $e)
		{
			return $this->loginRedirectFixture('User was not found.');
		}
		catch (UserNotActivatedException $e)
		{
			return $this->loginRedirectFixture('User is not activated.');
		}
		catch (UserSuspendedException $e)
		{
			return $this->loginRedirectFixture('User is suspended.');
		}
		catch (UserBannedException $e)
		{
			return $this->loginRedirectFixture('User is banned.');
		}

		return Redirect::route('larapress.cp.dashboard.get');
	}

	/**
	 * Logout
	 *
	 * If you're actually logged in, it'll log you out and show a message.
	 * Else it will silently redirect you to the login form.
	 *
	 * @return Redirect
	 */
	public function getLogout()
	{
		if ( Sentry::check() )
		{
			Sentry::logout();
			Session::flash('success', 'You have successfully logged out.');
		}

		return Redirect::route('larapress.home.login.get');
	}

	/**
	 * Reset Password
	 *
	 * Gives you the opportunity to reset you password easily.
	 *
	 * @return View
	 */
	public function getResetPassword()
	{
		Helpers::setPageTitle('Reset Password');
		Captcha::shareDataToViews();

		return View::make('larapress::pages.home.reset-password');
	}

	/**
	 * Redirect to the reset password route with a flash message keeping the input
	 *
	 * @param string $message The error message
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function resetPasswordRedirectFixture($message)
	{
		return Helpers::redirectWithFlashMessage('error', $message, 'larapress.home.reset.password.get')
			->withInput(Input::all());
	}

	/**
	 * Reset Password
	 *
	 * This will try and send you a reset password link.
	 * It is going to fail on wrong input or problems when sending the mail.
	 * No matter what happens, the user will get a flash message!
	 *
	 * @return Redirect
	 */
	public function postResetPassword()
	{
		try
		{
			Narrator::resetPassword();
		}
		catch (UserNotFoundException $e)
		{
			return $this->resetPasswordRedirectFixture('User was not found.');
		}
		catch (MailException $e)
		{
			return $this->resetPasswordRedirectFixture($e->getMessage());
		}

		return Helpers::redirectWithFlashMessage(
			'success',
			'Now please check your email account for further instructions!',
			'larapress.home.reset.password.get'
		);
	}

	/**
	 * Send New Password
	 *
	 * Once a user opens up a password reset email and clicks on the link he'll end up here.
	 * This verifies that the request is legit and sends an email containing a new password to the user.
	 *
	 * Regarding errors there's something special.
	 * The user will be redirected to the reset password view with a flash message when the reset fails.
	 * But if his request was not legit he'll simply get a 404 error.
	 * On success the user will be redirected to the login page with a flash message and get the new password via email.
	 *
	 * @param int $id The user id
	 * @param string $reset_code The (sentry) password reset code
	 * @return Redirect | Response
	 */
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
			return $this->resetPasswordRedirectFixture('Resetting your password failed. ' .
				'Please try again later or contact the administrator.');
		}
		catch (PasswordResetCodeInvalidException $e)
		{
			return Helpers::force404();
		}
		catch (MailException $e)
		{
			return $this->resetPasswordRedirectFixture($e->getMessage());
		}

		return Helpers::redirectWithFlashMessage(
			'success',
			'Now please check your email account for the new password!',
			'larapress.home.login.get'
		);
	}

}
