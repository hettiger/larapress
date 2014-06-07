<?php namespace Larapress\Tests\Filters\Backend;

use Larapress\Tests\Filters\Backend\Proxies\AccessBackendFilterProxy;
use Mockery;
use Mockery\Mock;
use PHPUnit_Framework_TestCase;

class AccessBackendFilterTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var Mock
	 */
	private $permission;

	/**
	 * @var Mock
	 */
	private $session;

	/**
	 * @var Mock
	 */
	private $redirect;

	public function setUp()
	{
		parent::setUp();

		$this->permission = Mockery::mock('\Larapress\Services\Permission');
		$this->session = Mockery::mock('\Illuminate\Session\Store');
		$this->redirect = Mockery::mock('\Illuminate\Routing\Redirector');
	}

	public function tearDown()
	{
		parent::tearDown();

		Mockery::close();
	}

	protected function getAccessBackendFilterInstance()
	{
		return new AccessBackendFilterProxy($this->permission, $this->session, $this->redirect);
	}

	/**
	 * @test filter() can redirect with flash message on missing permissions
	 */
	public function filter_can_redirect_with_flash_message_on_missing_permissions()
	{
		$this->permission->shouldReceive('has')->with('access.backend')->once()
			->andThrow('Larapress\Exceptions\PermissionMissingException', 'foo');
		$this->session->shouldReceive('flash')->with('error', 'foo')->once();
		$this->redirect->shouldReceive('route')->with('larapress.home.login.get')->once()->andReturn('bar');
		$filter = $this->getAccessBackendFilterInstance();

		$this->assertEquals('bar', $filter->filter());
	}

	/**
	 * @test filter() returns null on given permissions
	 */
	public function filter_returns_null_on_given_permissions()
	{
		$this->permission->shouldReceive('has')->with('access.backend')->andReturn(true);
		$filter = $this->getAccessBackendFilterInstance();

		$this->assertNull($filter->filter());
	}

}
