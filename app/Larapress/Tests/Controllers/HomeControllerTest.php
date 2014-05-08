<?php namespace Larapress\Tests\Controllers;

use Captcha;
use Config;
use Helpers;
use Larapress\Tests\TestCase;
use Narrator;
use Permission;
use Sentry;

class HomeControllerTest extends TestCase
{
    private $backend_route;

    public function setUp()
    {
        parent::setUp();

        $this->backend_route = Config::get('larapress.urls.backend');
    }

    /*
    |--------------------------------------------------------------------------
    | HomeController@getIndex Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the HomeController@getIndex method
    |
    */

    public function test_can_redirect_to_the_login_page_on_missing_permissions()
    {
        Permission::shouldReceive('has')->once()->andThrow('\Larapress\Exceptions\PermissionMissingException', 'error');

        $this->call('GET', $this->backend_route);
        $this->assertRedirectedToRoute('larapress.home.login.get');
    }

    public function test_can_redirect_to_the_dashboard_on_given_permissions()
    {
        Permission::shouldReceive('has')->once()->andReturn(true);

        $this->call('GET', $this->backend_route);
        $this->assertRedirectedToRoute('larapress.cp.dashboard.get');
    }

    /*
    |--------------------------------------------------------------------------
    | HomeController@getLogin Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the HomeController@getLogin method
    |
    */

    public function test_can_browse_the_login_page()
    {
        $this->route('GET', 'larapress.home.login.get');

        $this->assertResponseOk();
    }

    /*
    |--------------------------------------------------------------------------
    | HomeController@postLogin Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the HomeController@postLogin method
    |
    */

    public function test_can_redirect_with_a_flash_message_on_missing_login()
    {
        Sentry::shouldReceive('authenticate')
            ->with(array('email' => 'foo', 'password' => 'bar'), false)
            ->once()
            ->andThrow('Cartalyst\Sentry\Users\LoginRequiredException');

        $this->route('POST', 'larapress.home.login.post', array(), array('email' => 'foo', 'password' => 'bar'));

        $this->assertRedirectedToRoute('larapress.home.login.get');
        $this->assertHasOldInput();
        $this->assertSessionHas('error', 'Login field is required.');
    }

    public function test_can_redirect_with_a_flash_message_on_missing_password()
    {
        Sentry::shouldReceive('authenticate')
            ->with(array('email' => 'foo', 'password' => 'bar'), false)
            ->once()
            ->andThrow('Cartalyst\Sentry\Users\PasswordRequiredException');

        $this->route('POST', 'larapress.home.login.post', array(), array('email' => 'foo', 'password' => 'bar'));

        $this->assertRedirectedToRoute('larapress.home.login.get');
        $this->assertHasOldInput();
        $this->assertSessionHas('error', 'Password field is required.');
    }

    public function test_can_redirect_with_a_flash_message_on_wrong_password()
    {
        Sentry::shouldReceive('authenticate')
            ->with(array('email' => 'foo', 'password' => 'bar'), false)
            ->once()
            ->andThrow('Cartalyst\Sentry\Users\WrongPasswordException');

        $this->route('POST', 'larapress.home.login.post', array(), array('email' => 'foo', 'password' => 'bar'));

        $this->assertRedirectedToRoute('larapress.home.login.get');
        $this->assertHasOldInput();
        $this->assertSessionHas('error', 'Wrong password, try again.');
    }

    public function test_can_redirect_with_a_flash_message_on_wrong_login()
    {
        Sentry::shouldReceive('authenticate')
            ->with(array('email' => 'foo', 'password' => 'bar'), false)
            ->once()
            ->andThrow('Cartalyst\Sentry\Users\UserNotFoundException');

        $this->route('POST', 'larapress.home.login.post', array(), array('email' => 'foo', 'password' => 'bar'));

        $this->assertRedirectedToRoute('larapress.home.login.get');
        $this->assertHasOldInput();
        $this->assertSessionHas('error', 'User was not found.');
    }

    public function test_can_redirect_with_a_flash_message_on_not_activated_user()
    {
        Sentry::shouldReceive('authenticate')
            ->with(array('email' => 'foo', 'password' => 'bar'), false)
            ->once()
            ->andThrow('Cartalyst\Sentry\Users\UserNotActivatedException');

        $this->route('POST', 'larapress.home.login.post', array(), array('email' => 'foo', 'password' => 'bar'));

        $this->assertRedirectedToRoute('larapress.home.login.get');
        $this->assertHasOldInput();
        $this->assertSessionHas('error', 'User is not activated.');
    }

    public function test_can_redirect_with_a_flash_message_on_suspended_user()
    {
        Sentry::shouldReceive('authenticate')
            ->with(array('email' => 'foo', 'password' => 'bar'), false)
            ->once()
            ->andThrow('Cartalyst\Sentry\Throttling\UserSuspendedException');

        $this->route('POST', 'larapress.home.login.post', array(), array('email' => 'foo', 'password' => 'bar'));

        $this->assertRedirectedToRoute('larapress.home.login.get');
        $this->assertHasOldInput();
        $this->assertSessionHas('error', 'User is suspended.');
    }

    public function test_can_redirect_with_a_flash_message_on_banned_user()
    {
        Sentry::shouldReceive('authenticate')
            ->with(array('email' => 'foo', 'password' => 'bar'), false)
            ->once()
            ->andThrow('Cartalyst\Sentry\Throttling\UserBannedException');

        $this->route('POST', 'larapress.home.login.post', array(), array('email' => 'foo', 'password' => 'bar'));

        $this->assertRedirectedToRoute('larapress.home.login.get');
        $this->assertHasOldInput();
        $this->assertSessionHas('error', 'User is banned.');
    }

    public function test_can_redirect_to_dashboard_on_success()
    {
        Sentry::shouldReceive('authenticate')->with(array('email' => 'foo', 'password' => 'bar'), false)->once();

        $this->route('POST', 'larapress.home.login.post', array(), array('email' => 'foo', 'password' => 'bar'));

        $this->assertRedirectedToRoute('larapress.cp.dashboard.get');
    }

    /*
    |--------------------------------------------------------------------------
    | HomeController@getLogout Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the HomeController@getLogout method
    |
    */

    public function test_can_silently_redirect_if_the_user_is_not_logged_in()
    {
        Sentry::shouldReceive('check')->once()->andReturn(false);
        Sentry::shouldReceive('logout')->never();

        $this->route('GET', 'larapress.home.logout.get');

        $this->assertRedirectedToRoute('larapress.home.login.get');
    }

    public function test_can_log_the_user_out_and_redirect_to_the_login_page_with_a_flash_message()
    {
        Sentry::shouldReceive('check')->once()->andReturn(true);
        Sentry::shouldReceive('logout')->once();

        $this->route('GET', 'larapress.home.logout.get');

        $this->assertRedirectedToRoute('larapress.home.login.get');
        $this->assertSessionHas('success', 'You have successfully logged out.');
    }

    /*
    |--------------------------------------------------------------------------
    | HomeController@getResetPassword Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the HomeController@getResetPassword method
    |
    */

    public function test_can_browse_the_get_reset_password_route()
    {
        Captcha::shouldReceive('shareDataToViews')->once();
        Captcha::shouldReceive('isRequired')->once();

        $this->route('GET', 'larapress.home.reset.password.get');

        $this->assertResponseOk();
    }

    /*
    |--------------------------------------------------------------------------
    | HomeController@postResetPassword Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the HomeController@postResetPassword method
    |
    */

    public function test_can_redirect_on_user_not_found_exception_with_flash_message_and_old_input()
    {
        Narrator::shouldReceive('resetPassword')->once()->andThrow('Cartalyst\Sentry\Users\UserNotFoundException');

        $this->route('POST', 'larapress.home.reset.password.post');

        $this->assertRedirectedToRoute('larapress.home.reset.password.get');
        $this->assertSessionHas('error', 'User was not found.');
        $this->assertHasOldInput();
    }

    public function test_can_redirect_on_mail_exception_with_flash_message_and_old_input()
    {
        Narrator::shouldReceive('resetPassword')->once()->andThrow('Larapress\Exceptions\MailException', 'foo');

        $this->route('POST', 'larapress.home.reset.password.post');

        $this->assertRedirectedToRoute('larapress.home.reset.password.get');
        $this->assertSessionHas('error', 'foo');
        $this->assertHasOldInput();
    }

    public function test_can_redirect_success_with_flash_message()
    {
        Narrator::shouldReceive('resetPassword')->once()->andReturnNull();

        $this->route('POST', 'larapress.home.reset.password.post');

        $this->assertRedirectedToRoute('larapress.home.reset.password.get');
        $this->assertSessionHas('success', 'Now please check your email account for further instructions!');
    }

    /*
    |--------------------------------------------------------------------------
    | HomeController@getSendNewPassword Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the HomeController@getSendNewPassword method
    |
    */

    public function test_can_return_a_404_response_on_user_not_found_exception()
    {
        Narrator::shouldReceive('sendNewPassword')->once()->andThrow('Cartalyst\Sentry\Users\UserNotFoundException');
        Helpers::shouldReceive('force404')->once();

        $this->route('GET', 'larapress.home.send.new.password.get');
    }

    public function test_can_redirect_on_password_reset_failed_exception_with_flash_message()
    {
        Narrator::shouldReceive('sendNewPassword')
            ->once()->andThrow('Larapress\Exceptions\PasswordResetFailedException');

        $this->route('GET', 'larapress.home.send.new.password.get');

        $this->assertRedirectedToRoute('larapress.home.reset.password.get');
        $this->assertSessionHas('error', 'Resetting your password failed. ' .
            'Please try again later or contact the administrator.');
    }

    public function test_can_return_a_404_response_on_password_reset_code_invalid_exception()
    {
        Narrator::shouldReceive('sendNewPassword')
            ->once()->andThrow('Larapress\Exceptions\PasswordResetCodeInvalidException');
        Helpers::shouldReceive('force404')->once();

        $this->route('GET', 'larapress.home.send.new.password.get');
    }

    public function test_can_redirect_on_mail_exception_with_flash_message()
    {
        Narrator::shouldReceive('sendNewPassword')
            ->once()->andThrow('Larapress\Exceptions\MailException', 'foo');

        $this->route('GET', 'larapress.home.send.new.password.get');

        $this->assertRedirectedToRoute('larapress.home.reset.password.get');
        $this->assertSessionHas('error', 'foo');
    }

    public function test_can_redirect_on_success_with_flash_message()
    {
        Narrator::shouldReceive('sendNewPassword')->once()->andReturnNull();

        $this->route('GET', 'larapress.home.send.new.password.get');

        $this->assertRedirectedToRoute('larapress.home.login.get');
        $this->assertSessionHas('success', 'Now please check your email account for the new password!');
    }

}
