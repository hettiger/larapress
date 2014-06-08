<?php namespace Larapress\Tests\Commands;

use Cartalyst\Sentry\Groups\GroupNotFoundException;
use Cartalyst\Sentry\Users\UserExistsException;
use Larapress\Tests\Commands\Proxies\GroupProxy;
use Larapress\Tests\Commands\Proxies\InstallCommandProxy;
use Mockery;
use Mockery\Mock;
use PHPUnit_Framework_TestCase;

class InstallCommandTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var Mock
	 */
	private $sentry;

	/**
	 * @var Mock
	 */
	private $mockably;

	public function setUp()
	{
		parent::setUp();

		$this->sentry = Mockery::mock('\Cartalyst\Sentry\Sentry');
		$this->mockably = Mockery::mock('\Larapress\Services\Mockably');
	}

	public function tearDown()
	{
		parent::tearDown();

		$_SERVER['error'] = array();
		$_SERVER['info'] = array();
		$_SERVER['call'] = array();

		Mockery::close();
	}

	protected function getInstallCommandInstance()
	{
		$this->mockably->shouldReceive('route')->with('larapress.home.login.get')->once()->andReturn('url');

		return new InstallCommandProxy($this->sentry, $this->mockably);
	}

	/**
	 * @test abort_command()
	 */
	public function abort_command()
	{
		$this->mockably->shouldReceive('mockable_die')->once();
		$installCommand = $this->getInstallCommandInstance();

		$installCommand->abort_command('foo');

		$this->assertEquals('migrate:reset', $_SERVER['call'][0]);
		$this->assertEquals('foo', $_SERVER['error'][0]);
	}

	/**
	 * @test create_user_groups() can return the admin group
	 */
	public function create_user_groups_can_return_the_admin_group()
	{
		$this->assertClassHasAttribute('groups', 'Larapress\Commands\InstallCommand');
		$this->sentry->shouldReceive('createGroup')->times(3)->andReturn('foo');
		$installCommand = $this->getInstallCommandInstance();

		$this->assertEquals('foo', $installCommand->create_user_groups());
	}

	/**
	 * @test create_user_groups() can abort on name required exception
	 */
	public function create_user_groups_can_abort_on_name_required_exception()
	{
		$this->mockably->shouldReceive('mockable_die')->once();
		$this->sentry->shouldReceive('createGroup')->once()
			->andThrow('Cartalyst\Sentry\Groups\NameRequiredException');
		$installCommand = $this->getInstallCommandInstance();

		$installCommand->create_user_groups();

		$this->assertEquals('migrate:reset', $_SERVER['call'][0]);
		$this->assertEquals('Name field is required', $_SERVER['error'][0]);
	}

	/**
	 * @test create_user_groups() can abort on group exists exception
	 */
	public function create_user_groups_can_abort_on_group_exists_exception()
	{
		$this->mockably->shouldReceive('mockable_die')->once();
		$this->sentry->shouldReceive('createGroup')->once()
			->andThrow('Cartalyst\Sentry\Groups\GroupExistsException');
		$installCommand = $this->getInstallCommandInstance();

		$installCommand->create_user_groups();

		$this->assertEquals('migrate:reset', $_SERVER['call'][0]);
		$this->assertEquals('Group already exists', $_SERVER['error'][0]);
	}

	/**
	 * @test handle_unexpected_value_exception() can handle user exists exception
	 */
	public function handle_unexpected_value_exception_can_handle_user_exists_exception()
	{
		$this->mockably->shouldReceive('mockable_die')->atLeast()->once();
		$installCommand = $this->getInstallCommandInstance();

		$exception = new UserExistsException;
		$installCommand->handle_unexpected_value_exception($exception);

		$this->assertEquals('migrate:reset', $_SERVER['call'][0]);
		$this->assertEquals('User with this login already exists.', $_SERVER['error'][0]);
	}

	/**
	 * @test handle_unexpected_value_exception() can handle group not found exception
	 */
	public function handle_unexpected_value_exception_can_handle_group_not_found_exception()
	{
		$this->mockably->shouldReceive('mockable_die')->atLeast()->once();
		$installCommand = $this->getInstallCommandInstance();

		$exception = new GroupNotFoundException;
		$installCommand->handle_unexpected_value_exception($exception);

		$this->assertEquals('migrate:reset', $_SERVER['call'][0]);
		$this->assertEquals('Group was not found.', $_SERVER['error'][0]);
	}

	/**
	 * @test handle_unexpected_value_exception() can handle random exceptions
	 */
	public function handle_unexpected_value_exception_can_handle_random_exceptions()
	{
		$this->mockably->shouldReceive('mockable_die')->atLeast()->once();
		$installCommand = $this->getInstallCommandInstance();

		$exception = Mockery::mock();
		$installCommand->handle_unexpected_value_exception($exception);

		$this->assertEquals('migrate:reset', $_SERVER['call'][0]);
		$this->assertEquals('Unexpected value error.', $_SERVER['error'][0]);
	}

	/**
	 * @test add_the_admin_user() can create user
	 */
	public function add_the_admin_user_can_create_user()
	{
		$credentials = array(
			'email'     => 'admin@example.com',
			'password'  => 'password',
			'activated' => true,
		);

		$user = Mockery::mock();
		$user->shouldReceive('addGroup')->with('foo')->once();
		$this->sentry->shouldReceive('createUser')->with($credentials)->once()->andReturn($user);
		$installCommand = $this->getInstallCommandInstance();

		$installCommand->add_the_admin_user('foo');
	}

	/**
	 * @test add_the_admin_user() can handle unexpected value exceptions
	 */
	public function add_the_admin_user_can_handle_unexpected_value_exceptions()
	{
		$this->sentry->shouldReceive('createUser')->withAnyArgs()->once()->andThrow('UnexpectedValueException');
		$this->mockably->shouldReceive('mockable_die')->once();
		$installCommand = $this->getInstallCommandInstance();

		$installCommand->add_the_admin_user('foo');

		$this->assertEquals('migrate:reset', $_SERVER['call'][0]);
		$this->assertEquals('Unexpected value error.', $_SERVER['error'][0]);
	}

	/**
	 * @test fire() can abort if group creation failed
	 */
	public function fire_can_abort_if_group_creation_failed()
	{
		$this->assertClassHasAttribute('groups', 'Larapress\Commands\InstallCommand');
		$this->sentry->shouldReceive('createGroup')->times(3)->andReturn('foo');
		$this->mockably->shouldReceive('mockable_die')->once();
		$installCommand = $this->getInstallCommandInstance();

		$installCommand->fire();

		$this->assertEquals('migrate', $_SERVER['call'][0]);
		$this->assertEquals('Installing larapress ...' . PHP_EOL, $_SERVER['info'][0]);
		$this->assertEquals('Failed creating the user groups.', $_SERVER['error'][0]);
	}

	/**
	 * @test fire() can install larapress successfully
	 */
	public function fire_can_install_larapress_successfully()
	{
		$admin_group = new GroupProxy;
		$this->assertClassHasAttribute('groups', 'Larapress\Commands\InstallCommand');
		$this->sentry->shouldReceive('createGroup')->times(3)->andReturn($admin_group);
		$user = Mockery::mock();
		$user->shouldReceive('addGroup')->once();
		$this->sentry->shouldReceive('createUser')->withAnyArgs()->once()->andReturn($user);
		$installCommand = $this->getInstallCommandInstance();

		$installCommand->fire();

		$this->assertEquals('migrate', $_SERVER['call'][0]);
		$this->assertEquals('Installing larapress ...' . PHP_EOL, $_SERVER['info'][0]);
		$this->assertEquals(PHP_EOL . 'Installation complete!' . PHP_EOL, $_SERVER['info'][1]);
		$this->assertEquals('Now please visit url and login.' . PHP_EOL, $_SERVER['info'][2]);
		$this->assertEquals('Credentials:', $_SERVER['info'][3]);
		$this->assertEquals('E-Mail: admin@example.com', $_SERVER['info'][4]);
		$this->assertEquals('Password: password' . PHP_EOL, $_SERVER['info'][5]);
		$this->assertEquals('Make sure you instantly update your credentials!', $_SERVER['info'][6]);
	}

}
