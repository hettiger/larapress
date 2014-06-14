<?php namespace Larapress\Controllers\Backend;

use Captcha;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Exception;
use Helpers;
use Input;
use Larapress\Exceptions\PasswordResetCodeInvalidException;
use Larapress\Exceptions\PermissionMissingException;
use Narrator;
use Permission;
use Redirect;
use Response;
use Sentry;
use Session;
use View;

class HomeController extends BackendBaseController {

	private $error_messages = array
	(
		'LoginRequiredException'       => 'Login field is required.',
		'PasswordRequiredException'    => 'Password field is required.',
		'WrongPasswordException'       => 'Wrong password, try again.',
		'UserNotFoundException'        => 'User was not found.',
		'UserNotActivatedException'    => 'User is not activated.',
		'UserSuspendedException'       => 'User is suspended.',
		'UserBannedException'          => 'User is banned.',
		'PasswordResetFailedException' => 'Resetting your password failed. Please try again later or contact the administrator.'
	);

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
	 * Login
	 *
	 * This method will be processed once you try to login.
	 * It redirects you either back to the login page with an error message or to the dashboard.
	 *
	 * @throws Exception
	 * @return Redirect
	 */
	public function postLogin()
	{
		try
		{
			$credentials = array(
				'email'    => Input::get('email'),
				'password' => Input::get('password')
			);

			Sentry::authenticate($credentials, false);
		}
		catch (Exception $e)
		{
			$error_message = Helpers::handleMultipleExceptions($e, $this->error_messages);

			return Helpers::redirectWithFlashMessage('error', $error_message, 'larapress.home.login.get')
				->withInput(Input::except('password'));
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
	 * Handle multiple Exceptions for the postResetPassword() and getSendNewPassword() methods
	 *
	 * See private $error_messages for handled exceptions.
	 * Besides of that the MailException is handled by this fixture.
	 *
	 * @param Exception $exception The caught exception
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function resetPasswordFixture($exception)
	{
		$this->error_messages['MailException'] = $exception->getMessage();
		$error_message = Helpers::handleMultipleExceptions($exception, $this->error_messages);

		return Helpers::redirectWithFlashMessage('error', $error_message, 'larapress.home.reset.password.get')
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
		catch (Exception $e)
		{
			return $this->resetPasswordFixture($e);
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
		catch (PasswordResetCodeInvalidException $e)
		{
			return Helpers::force404();
		}
		catch (Exception $e)
		{
			return $this->resetPasswordFixture($e);
		}

		return Helpers::redirectWithFlashMessage(
			'success',
			'Now please check your email account for the new password!',
			'larapress.home.login.get'
		);
	}

}
