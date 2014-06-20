<?php namespace Larapress\Tests\Services;

use Larapress\Services\Permission;
use Larapress\Tests\TestCase;
use Mockery;
use Mockery\Mock;

class PermissionTest extends TestCase {

	/**
	 * @var Mock
	 */
	protected $sentry;

	public function setUp()
	{
		parent::setUp();

		$this->sentry = Mockery::mock('Cartalyst\Sentry\Sentry');
	}

	protected function getPermissionInstance()
	{
		return new Permission($this->sentry);
	}

	/**
	 * @test has() throws an exception when user is not logged in
	 * @expectedException \Larapress\Exceptions\PermissionMissingException
	 * @expectedExceptionMessage User is not logged in.
	 */
	public function has_throws_an_exception_when_user_is_not_logged_in()
	{
		$this->sentry->shouldReceive('check')->once()->andReturn(false);
		$permission = $this->getPermissionInstance();

		$permission->has('foo');
	}

	/**
	 * @test has() throws an exception on missing permissions
	 * @expectedException \Larapress\Exceptions\PermissionMissingException
	 * @expectedExceptionMessage User is missing permissions.
	 */
	public function has_throws_an_exception_on_missing_permissions()
	{
		$this->sentry->shouldReceive('check')->once()->andReturn(true);

		$user = Mockery::mock();
		$user->shouldReceive('hasAccess')->once()->andReturn(false);

		$this->sentry->shouldReceive('getUser')->once()->andReturn($user);
		$permission = $this->getPermissionInstance();

		$permission->has('foo');
	}

	/**
	 * @test has() returns true if the user is logged in and has permissions
	 */
	public function has_returns_true_if_the_user_is_logged_in_and_has_permissions()
	{
		$this->sentry->shouldReceive('check')->once()->andReturn(true);

		$user = Mockery::mock();
		$user->shouldReceive('hasAccess')->once()->andReturn(true);

		$this->sentry->shouldReceive('getUser')->once()->andReturn($user);
		$permission = $this->getPermissionInstance();

		$this->assertTrue($permission->has('foo'));
	}

}
