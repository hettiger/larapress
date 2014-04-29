<?php namespace Larapress\Tests\Services;

use Larapress\Tests\TestCase;
use Mockery;
use Permission;
use Sentry;

class PermissionTest extends TestCase
{

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
        Sentry::shouldReceive('check')->once()->andReturn(false);

        Permission::has('foo');
    }

    /**
     * @expectedException \Larapress\Exceptions\PermissionMissingException
     * @expectedExceptionMessage User is missing permissions.
     */
    public function test_can_throw_an_exception_when_user_is_missing_permissions()
    {
        Sentry::shouldReceive('check')->once()->andReturn(true);

        $get_user_mock = Mockery::mock();
        $get_user_mock->shouldReceive('hasAccess')->once()->andReturn(false);

        Sentry::shouldReceive('getUser')->once()->andReturn($get_user_mock);

        Permission::has('foo');
    }

    public function test_can_return_true_if_user_is_logged_in_and_has_permissions()
    {
        Sentry::shouldReceive('check')->once()->andReturn(true);

        $get_user_mock = Mockery::mock();
        $get_user_mock->shouldReceive('hasAccess')->once()->andReturn(true);

        Sentry::shouldReceive('getUser')->once()->andReturn($get_user_mock);

        $this->assertTrue(Permission::has('foo'));
    }

}
