<?php namespace Larapress\Controllers\Backend;

use Cartalyst\Sentry\Sentry;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Exception;
use Illuminate\Http\Request as Input;
use Illuminate\Routing\Redirector as Redirect;
use Illuminate\Session\Store as Session;
use Illuminate\Support\Facades\Response;
use Illuminate\View\Factory as View;
use Larapress\Exceptions\PasswordResetCodeInvalidException;
use Larapress\Exceptions\PermissionMissingException;
use Larapress\Interfaces\CaptchaInterface as Captcha;
use Larapress\Interfaces\HelpersInterface as Helpers;
use Larapress\Interfaces\NarratorInterface as Narrator;
use Larapress\Interfaces\PermissionInterface as Permission;

class HomeController extends BackendBaseController {

	/**
	 * @var \Larapress\Interfaces\PermissionInterface
	 */
	private $permission;

	/**
	 * @var \Illuminate\Routing\Redirector
	 */
	private $redirect;

	/**
	 * @var \Illuminate\View\Factory
	 */
	private $view;

	/**
	 * @var \Cartalyst\Sentry\Sentry
	 */
	private $sentry;

	/**
	 * @var \Larapress\Interfaces\CaptchaInterface
	 */
	private $captcha;

	/**
	 * @var \Illuminate\Http\Request
	 */
	private $input;

	/**
	 * @var \Larapress\Interfaces\NarratorInterface
	 */
	private $narrator;

	/**
	 * @var \Illuminate\Session\Store
	 */
	private $session;

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

	public function __construct(
		Permission $permission, Redirect $redirect, Helpers $helpers,
		View $view, Sentry $sentry, Captcha $captcha,
		Input $input, Narrator $narrator, Session $session
	) {
		parent::__construct($helpers);

		$this->permission = $permission;
		$this->redirect = $redirect;
		$this->helpers = $helpers;
		$this->view = $view;
		$this->sentry = $sentry;
		$this->captcha = $captcha;
		$this->input = $input;
		$this->narrator = $narrator;
		$this->session = $session;
	}

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
			$this->permission->has('access.backend');
		}
		catch (PermissionMissingException $e)
		{
			return $this->redirect->route('larapress.home.login.get');
		}

		return $this->redirect->route('larapress.cp.dashboard.get');
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
		$this->helpers->setPageTitle('Login');

		return $this->view->make('larapress::pages.home.login');
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
				'email'    => $this->input->get('email'),
				'password' => $this->input->get('password')
			);

			$this->sentry->authenticate($credentials, false);
		}
		catch (Exception $e)
		{
			$error_message = $this->helpers->handleMultipleExceptions($e, $this->error_messages);

			return $this->helpers->redirectWithFlashMessage('error', $error_message, 'larapress.home.login.get')
				->withInput($this->input->except('password'));
		}

		return $this->redirect->route('larapress.cp.dashboard.get');
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
		if ( $this->sentry->check() )
		{
			$this->sentry->logout();
			$this->session->flash('success', 'You have successfully logged out.');
		}

		return $this->redirect->route('larapress.home.login.get');
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
		$this->helpers->setPageTitle('Reset Password');
		$this->captcha->shareDataToViews();

		return $this->view->make('larapress::pages.home.reset-password');
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
		$error_message = $this->helpers->handleMultipleExceptions($exception, $this->error_messages);

		return $this->helpers->redirectWithFlashMessage('error', $error_message, 'larapress.home.reset.password.get')
			->withInput($this->input->all());
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
			$this->narrator->resetPassword();
		}
		catch (Exception $e)
		{
			return $this->resetPasswordFixture($e);
		}

		return $this->helpers->redirectWithFlashMessage(
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
			$this->narrator->sendNewPassword($id, $reset_code);
		}
		catch (UserNotFoundException $e)
		{
			return $this->helpers->force404();
		}
		catch (PasswordResetCodeInvalidException $e)
		{
			return $this->helpers->force404();
		}
		catch (Exception $e)
		{
			return $this->resetPasswordFixture($e);
		}

		return $this->helpers->redirectWithFlashMessage(
			'success',
			'Now please check your email account for the new password!',
			'larapress.home.login.get'
		);
	}

}
