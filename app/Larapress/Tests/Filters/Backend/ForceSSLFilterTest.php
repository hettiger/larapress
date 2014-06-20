<?php namespace Larapress\Tests\Filters\Backend;

use Larapress\Tests\Filters\Backend\Proxies\ForceSSLFilterProxy;
use Larapress\Tests\TestCase;
use Mockery;
use Mockery\Mock;

class ForceSSLFilterTest extends TestCase {

	/**
	 * @var Mock
	 */
	private $config;

	/**
	 * @var Mock
	 */
	private $helpers;

	public function setUp()
	{
		parent::setUp();

		$this->config = Mockery::mock('\Illuminate\Config\Repository');
		$this->helpers = Mockery::mock('\Larapress\Services\Helpers');
	}

	protected function getForceSSLFilterInstance()
	{
		return new ForceSSLFilterProxy($this->config, $this->helpers);
	}

	/**
	 * @test filter() does force SSL if enabled by configuration
	 */
	public function filter_does_force_ssl_if_enabled_by_configuration()
	{
		$this->config->shouldReceive('get')->with('larapress.settings.ssl')
			->once()->andReturn(true);
		$this->helpers->shouldReceive('forceSSL')->withNoArgs()->once()->andReturn('foo');
		$filter = $this->getForceSSLFilterInstance();

		$this->assertEquals('foo', $filter->filter());
	}

	/**
	 * @test filter() returns null if disabled by configuration
	 */
	public function filter_returns_null_if_disabled_by_configuration()
	{
		$this->config->shouldReceive('get')->with('larapress.settings.ssl')
			->once()->andReturn(false);
		$filter = $this->getForceSSLFilterInstance();

		$this->assertNull($filter->filter());
	}

}
