<?php namespace Larapress\Tests\Filters;

use Larapress\Tests\TestCase;
use Permission;
use Route;

class AccessBackendFilterTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();

        Route::enableFilters();
    }

    public function test_can_redirect_on_missing_permissions()
    {
        Permission::shouldReceive('has')->once()->andThrow('\Larapress\Exceptions\PermissionMissingException', 'error');

        $this->route('GET', 'larapress.cp.dashboard.get');

        $this->assertRedirectedToRoute('larapress.home.login.get');
        $this->assertSessionHas('error', 'error');
    }

    public function test_can_access_on_given_permissions()
    {
        Permission::shouldReceive('has')->once()->andReturn(true);

        $this->route('GET', 'larapress.cp.dashboard.get');

        $this->assertResponseOk();
    }

}
