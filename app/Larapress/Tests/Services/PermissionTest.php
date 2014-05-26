<?php namespace Larapress\Tests\Services;

use Larapress\Services\Permission;
use Mockery;
use Mockery\Mock;
use PHPUnit_Framework_TestCase;

class PermissionTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Mock
     */
    protected $sentry;

    public function setUp()
    {
        parent::setUp();

        $this->sentry = Mockery::mock('Cartalyst\Sentry\Sentry');
    }

    public function tearDown()
    {
        parent::tearDown();

        Mockery::close();
    }

    protected function getPermissionInstance()
    {
        return new Permission($this->sentry);
    }

    /*
    |--------------------------------------------------------------------------
    | Permission::has() Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the Permission::has() method
    |
    */

    /**
     * @expectedException \Larapress\Exceptions\PermissionMissingException
     * @expectedExceptionMessage User is not logged in.
     */
    public function test_can_throw_an_exception_when_user_is_not_logged_in()
    {
        $this->sentry->shouldReceive('check')->once()->andReturn(false);
        $permission = $this->getPermissionInstance();

        $permission->has('foo');
    }

    /**
     * @expectedException \Larapress\Exceptions\PermissionMissingException
     * @expectedExceptionMessage User is missing permissions.
     */
    public function test_can_throw_an_exception_when_user_is_missing_permissions()
    {
        $this->sentry->shouldReceive('check')->once()->andReturn(true);

        $get_user_mock = Mockery::mock();
        $get_user_mock->shouldReceive('hasAccess')->once()->andReturn(false);

        $this->sentry->shouldReceive('getUser')->once()->andReturn($get_user_mock);
        $permission = $this->getPermissionInstance();

        $permission->has('foo');
    }

    public function test_can_return_true_if_user_is_logged_in_and_has_permissions()
    {
        $this->sentry->shouldReceive('check')->once()->andReturn(true);

        $get_user_mock = Mockery::mock();
        $get_user_mock->shouldReceive('hasAccess')->once()->andReturn(true);

        $this->sentry->shouldReceive('getUser')->once()->andReturn($get_user_mock);
        $permission = $this->getPermissionInstance();

        $this->assertTrue($permission->has('foo'));
    }

}
