<?php namespace Larapress\Tests\Controllers\Backend;

use Larapress\Tests\Controllers\Backend\Proxies\HomeControllerProxy;
use Larapress\Tests\Controllers\Backend\Templates\BackendControllerTestCase;
use Mockery;
use Mockery\Mock;

class HomeControllerTest extends BackendControllerTestCase {

	/**
	 * @var Mock
	 */
	private $permission;

	/**
	 * @var Mock
	 */
	private $redirect;

	/**
	 * @var Mock
	 */
	private $view;

	/**
	 * @var Mock
	 */
	private $sentry;

	/**
	 * @var Mock
	 */
	private $captcha;

	/**
	 * @var Mock
	 */
	private $input;

	/**
	 * @var Mock
	 */
	private $narrator;

	/**
	 * @var Mock
	 */
	private $session;

	private $credentials = array
	(
		'email'    => 'admin@example.com',
		'password' => 'password'
	);

	private $errorMessages = array
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

	public function setUp()
	{
		parent::setUp();

		$this->permission = Mockery::mock('\Larapress\Interfaces\PermissionInterface');
		$this->redirect = Mockery::mock('\Illuminate\Routing\Redirector');
		$this->view = Mockery::mock('\Illuminate\View\Factory');
		$this->sentry = Mockery::mock('\Cartalyst\Sentry\Sentry');
		$this->captcha = Mockery::mock('\Larapress\Interfaces\CaptchaInterface');
		$this->input = Mockery::mock('\Illuminate\Http\Request');
		$this->narrator = Mockery::mock('\Larapress\Interfaces\NarratorInterface');
		$this->session = Mockery::mock('\Illuminate\Session\Store');
	}

	protected function getHomeControllerInstance()
	{
		return new HomeControllerProxy(
			$this->permission, $this->redirect, $this->helpers,
			$this->view, $this->sentry, $this->captcha,
			$this->input, $this->narrator, $this->session
		);
	}

	protected function credentialsInputFixture()
	{
		$this->input->shouldReceive('get')->with('email')->once()->andReturn($this->credentials['email']);
		$this->input->shouldReceive('get')->with('password')->once()->andReturn($this->credentials['password']);
	}

	protected function redirectorFixture()
	{
		$redirector = Mockery::mock();
		$redirector->shouldReceive('withInput')->withAnyArgs()->once()->andReturn('redirect');

		return $redirector;
	}

	/**
	 * @test error_messages
	 */
	public function error_messages()
	{
		$controller = $this->getHomeControllerInstance();

		$this->assertEquals($this->errorMessages, $controller->getErrorMessages());
	}

	/**
	 * @test getIndex() redirects to the login view on missing permissions
	 */
	public function getIndex_redirects_to_the_login_view_on_missing_permissions()
	{
		$this->permission->shouldReceive('has')->with('access.backend')->once()
			->andThrow('Larapress\Exceptions\PermissionMissingException');
		$this->redirect->shouldReceive('route')->with('larapress.home.login.get')->once()->andReturn('foo');
		$controller = $this->getHomeControllerInstance();

		$this->assertEquals('foo', $controller->getIndex());
	}

	/**
	 * @test getIndex() redirects to the dashboard if logged in with matching permissions
	 */
	public function getIndex_redirects_to_the_dashboard_if_logged_in_with_matching_permissions()
	{
		$this->permission->shouldReceive('has')->with('access.backend')->once();
		$this->redirect->shouldReceive('route')->with('larapress.cp.dashboard.get')->once()->andReturn('foo');
		$controller = $this->getHomeControllerInstance();

		$this->assertEquals('foo', $controller->getIndex());
	}

	/**
	 * @test getLogin() sets the title and makes the login view
	 */
	public function getLogin_sets_the_title_and_makes_the_login_view()
	{
		$this->helpers->shouldReceive('setPageTitle')->with('Login')->once();
		$this->view->shouldReceive('make')->with('larapress::pages.home.login')->once()->andReturn('foo');
		$controller = $this->getHomeControllerInstance();

		$this->assertEquals('foo', $controller->getLogin());
	}

	/**
	 * @test postLogin() redirects to the dashboard on successful login
	 */
	public function postLogin_redirects_to_the_dashboard_on_successful_login()
	{
		$this->credentialsInputFixture();
		$this->sentry->shouldReceive('authenticate')->with($this->credentials, false)->once();
		$this->redirect->shouldReceive('route')->with('larapress.cp.dashboard.get')->once()->andReturn('foo');
		$controller = $this->getHomeControllerInstance();

		$this->assertEquals('foo', $controller->postLogin());
	}

	/**
	 * @test postLogin() redirects with a flash message and input except password on exceptions
	 */
	public function postLogin_redirects_with_a_flash_message_and_input_except_password_on_exceptions()
	{
		$this->credentialsInputFixture();
		$this->sentry->shouldReceive('authenticate')->with($this->credentials, false)->once()
			->andThrow('Exception');
		$this->helpers->shouldReceive('handleMultipleExceptions')->once()->andReturn('foo');

		$this->input->shouldReceive('except')->with('password')->once();
		$redirector = $this->redirectorFixture();

		$this->helpers->shouldReceive('redirectWithFlashMessage')
			->with('error', 'foo', 'larapress.home.login.get')->once()->andReturn($redirector);
		$controller = $this->getHomeControllerInstance();

		$this->assertEquals('redirect', $controller->postLogin());
	}

	/**
	 * @test getLogout() logs the user out if required and redirects with a flash message
	 */
	public function getLogout_logs_the_user_out_if_required_and_redirects_with_a_flash_message()
	{
		$this->sentry->shouldReceive('check')->withNoArgs()->once()->andReturn(true);
		$this->sentry->shouldReceive('logout')->withNoArgs()->once();
		$this->session->shouldReceive('flash')->with('success', 'You have successfully logged out.')->once();
		$this->redirect->shouldReceive('route')->with('larapress.home.login.get')->once()->andReturn('foo');
		$controller = $this->getHomeControllerInstance();

		$this->assertEquals('foo', $controller->getLogout());
	}

	/**
	 * @test getLogout() Redirects with no flash message or logout call when no user is logged in
	 */
	public function getLogout_redirects_with_no_flash_message_or_logout_call_when_no_user_is_logged_in()
	{
		$this->sentry->shouldReceive('check')->withNoArgs()->once()->andReturn(false);
		$this->redirect->shouldReceive('route')->with('larapress.home.login.get')->once()->andReturn('foo');
		$controller = $this->getHomeControllerInstance();

		$this->assertEquals('foo', $controller->getLogout());
	}

	/**
	 * @test getResetPassword() sets the page title shares data to the views and makes the reset password view
	 */
	public function getResetPassword_sets_the_page_title_shares_data_to_the_views_and_makes_the_reset_password_view()
	{
		$this->helpers->shouldReceive('setPageTitle')->with('Reset Password')->once();
		$this->captcha->shouldReceive('shareDataToViews')->withNoArgs()->once();
		$this->view->shouldReceive('make')->with('larapress::pages.home.reset-password')
			->once()->andReturn('foo');
		$controller = $this->getHomeControllerInstance();

		$this->assertEquals('foo', $controller->getResetPassword());
	}

	/**
	 * @test resetPasswordFixture() adds a error message for mail exceptions and handles multiple exceptions
	 */
	public function resetPasswordFixture_adds_a_error_message_for_mail_exceptions_and_handles_multiple_exceptions()
	{
		$exception = Mockery::mock();
		$exception->shouldReceive('getMessage')->withNoArgs()->once()->andReturn('error message');
		$this->errorMessages['MailException'] = 'error message';

		$this->helpers->shouldReceive('handleMultipleExceptions')->once()->andReturn('foo');
		$this->input->shouldReceive('all')->withNoArgs()->once();
		$redirector = $this->redirectorFixture();
		$this->helpers->shouldReceive('redirectWithFlashMessage')
			->with('error', 'foo', 'larapress.home.reset.password.get')->once()->andReturn($redirector);

		$controller = $this->getHomeControllerInstance();

		$this->assertEquals('redirect', $controller->resetPasswordFixture($exception));
		$this->assertEquals($this->errorMessages, $controller->getErrorMessages());
	}

	/**
	 * @test postResetPassword() can reset the password and redirect with a flash message
	 */
	public function postResetPassword_can_reset_the_password_and_redirect_with_a_flash_message()
	{
		$this->narrator->shouldReceive('resetPassword')->withNoArgs()->once();
		$this->helpers->shouldReceive('redirectWithFlashMessage')->with(
			'success',
			'Now please check your email account for further instructions!',
			'larapress.home.reset.password.get'
		)->once()->andReturn('foo');
		$controller = $this->getHomeControllerInstance();

		$this->assertEquals('foo', $controller->postResetPassword());
	}

	/**
	 * @test postResetPassword() can handle exceptions on failure
	 */
	public function postResetPassword_can_handle_exceptions_on_failure()
	{
		$this->narrator->shouldReceive('resetPassword')->withNoArgs()->once()->andThrow('Exception');
		$this->helpers->shouldReceive('handleMultipleExceptions')->withAnyArgs()->once();
		$redirector = $this->redirectorFixture();
		$this->helpers->shouldReceive('redirectWithFlashMessage')->withAnyArgs()->once()->andReturn($redirector);
		$this->input->shouldReceive('all')->withNoArgs()->once();
		$controller = $this->getHomeControllerInstance();

		$this->assertEquals('redirect', $controller->postResetPassword());
	}

	/**
	 * @test getSendNewPassword() can send a new password and redirect with a flash message
	 */
	public function getSendNewPassword_can_send_a_new_password_and_redirect_with_a_flash_message()
	{
		$this->narrator->shouldReceive('sendNewPassword')->with(1, 2)->once();
		$this->helpers->shouldReceive('redirectWithFlashMessage')->with(
			'success',
			'Now please check your email account for the new password!',
			'larapress.home.login.get'
		)->once()->andReturn('foo');
		$controller = $this->getHomeControllerInstance();

		$this->assertEquals('foo', $controller->getSendNewPassword(1, 2));
	}

	/**
	 * @test getSendNewPassword() forces 404 on user not found exceptions
	 */
	public function getSendNewPassword_forces_404_on_user_not_found_exceptions()
	{
		$this->narrator->shouldReceive('sendNewPassword')->with(1, 2)->once()
			->andThrow('Cartalyst\Sentry\Users\UserNotFoundException');
		$this->helpers->shouldReceive('force404')->withNoArgs()->once()->andReturn('foo');
		$controller = $this->getHomeControllerInstance();

		$this->assertEquals('foo', $controller->getSendNewPassword(1, 2));
	}

	/**
	 * @test getSendNewPassword() forces 404 on password reset code invalid exceptions
	 */
	public function getSendNewPassword_forces_404_on_password_reset_code_invalid_exceptions()
	{
		$this->narrator->shouldReceive('sendNewPassword')->with(1, 2)->once()
			->andThrow('Larapress\Exceptions\PasswordResetCodeInvalidException');
		$this->helpers->shouldReceive('force404')->withNoArgs()->once()->andReturn('foo');
		$controller = $this->getHomeControllerInstance();

		$this->assertEquals('foo', $controller->getSendNewPassword(1, 2));
	}

	/**
	 * @test getSendNewPassword() can handle multiple other exceptions on failure
	 */
	public function getSendNewPassword_can_handle_multiple_other_exceptions_on_failure()
	{
		$this->narrator->shouldReceive('sendNewPassword')->with(1, 2)->once()->andThrow('Exception');
		$this->helpers->shouldReceive('handleMultipleExceptions')->withAnyArgs()->once();
		$redirector = $this->redirectorFixture();
		$this->helpers->shouldReceive('redirectWithFlashMessage')->withAnyArgs()->once()->andReturn($redirector);
		$this->input->shouldReceive('all')->withNoArgs()->once();
		$controller = $this->getHomeControllerInstance();

		$this->assertEquals('redirect', $controller->getSendNewPassword(1, 2));
	}

}
