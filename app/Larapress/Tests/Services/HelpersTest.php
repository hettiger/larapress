<?php namespace Larapress\Tests\Services;

use BadMethodCallException;
use Larapress\Services\Helpers;
use Mockery;
use Mockery\Mock;
use PHPUnit_Framework_TestCase;

class HelpersTest extends PHPUnit_Framework_TestCase {

	/**
	 * @var Mock
	 */
	protected $config;

	/**
	 * @var Mock
	 */
	protected $lang;

	/**
	 * @var Mock
	 */
	protected $view;

	/**
	 * @var Mock
	 */
	protected $mockably;

	/**
	 * @var Mock
	 */
	protected $log;

	/**
	 * @var Mock
	 */
	protected $request;

	/**
	 * @var Mock
	 */
	protected $session;

	/**
	 * @var Mock
	 */
	protected $db;

	/**
	 * @var Mock
	 */
	protected $redirect;

	/**
	 * @var Mock
	 */
	private $baseController;

	public function setUp()
	{
		parent::setUp();

		$this->config = Mockery::mock('\Illuminate\Config\Repository');
		$this->lang = Mockery::mock('\Illuminate\Translation\Translator');
		$this->view = Mockery::mock('\Illuminate\View\Factory');
		$this->mockably = Mockery::mock('\Larapress\Services\Mockably');
		$this->log = Mockery::mock('\Monolog\Logger');
		$this->request = Mockery::mock('\Illuminate\Http\Request');
		$this->session = Mockery::mock('\Illuminate\Session\Store');
		$this->db = Mockery::mock('\Illuminate\Database\Connection');
		$this->redirect = Mockery::mock('\Illuminate\Routing\Redirector');
		$this->baseController = Mockery::mock('\Larapress\Controllers\BaseController');
	}

	public function tearDown()
	{
		parent::tearDown();

		Mockery::close();
	}

	protected function getHelpersInstance()
	{
		return new Helpers(
			$this->config,
			$this->lang,
			$this->view,
			$this->mockably,
			$this->log,
			$this->request,
			$this->session,
			$this->db,
			$this->redirect,
			$this->baseController
		);
	}

	/**
	 * @test setPageTitle()
	 */
	public function setPageTitle()
	{
		$this->config->shouldReceive('get')->with('larapress.names.cms')->once()->andReturn('foo');
		$this->lang->shouldReceive('get')->with('larapress::general.bar')->once()->andReturn('bar');
		$this->view->shouldReceive('share')->with('title', 'foo | bar')->once();
		$helpers = $this->getHelpersInstance();

		$helpers->setPageTitle('bar');
	}

	/**
	 * @test getCurrentTimeDifference() can throw an exception on bad calls
	 * @expectedException BadMethodCallException
	 */
	public function getCurrentTimeDifference_can_throw_an_exception_on_bad_calls()
	{
		$this->mockably->shouldDeferMissing();
		$helpers = $this->getHelpersInstance();

		$helpers->getCurrentTimeDifference(0.00, 'foo');
	}

	/**
	 * @test getCurrentTimeDifference() returns difference in minutes per default
	 */
	public function getCurrentTimeDifference_returns_difference_in_minutes_per_default()
	{
		$this->mockably->shouldReceive('microtime')->once()->andReturn(60.00);
		$helpers = $this->getHelpersInstance();

		$this->assertEquals(1, $helpers->getCurrentTimeDifference(0.00));
	}

	/**
	 * @test getCurrentTimeDifference() can return difference in minutes
	 */
	public function getCurrentTimeDifference_can_return_difference_in_minutes()
	{
		$this->mockably->shouldReceive('microtime')->once()->andReturn(60.00);
		$helpers = $this->getHelpersInstance();

		$this->assertEquals(1, $helpers->getCurrentTimeDifference(0.00, 'm'));
	}

	/**
	 * @test getCurrentTimeDifference() rounds minutes correctly
	 */
	public function getCurrentTimeDifference_rounds_minutes_correctly()
	{
		$this->mockably->shouldReceive('microtime')->once()->andReturn(100.00);
		$helpers = $this->getHelpersInstance();

		$this->assertEquals(1, $helpers->getCurrentTimeDifference(0.00, 'm'));
	}

	/**
	 * @test getCurrentTimeDifference() can return difference in seconds
	 */
	public function getCurrentTimeDifference_can_return_difference_in_seconds()
	{
		$this->mockably->shouldReceive('microtime')->once()->andReturn(60.00);
		$helpers = $this->getHelpersInstance();

		$this->assertEquals(30, $helpers->getCurrentTimeDifference(30.00, 's'));
	}

	/**
	 * @test getCurrentTimeDifference() can return difference in milliseconds
	 */
	public function getCurrentTimeDifference_can_return_difference_in_milliseconds()
	{
		$this->mockably->shouldReceive('microtime')->once()->andReturn(60.00);
		$helpers = $this->getHelpersInstance();

		$this->assertEquals(30000, $helpers->getCurrentTimeDifference(30.00, 'ms'));
	}

	/**
	 * @test logPerformance()
	 */
	public function logPerformance()
	{
		$this->request->shouldReceive('getRequestUri')->once()->andReturn('url');
		$this->session->shouldReceive('get')->with('start.time')->once()->andReturn(30.00);
		$this->mockably->shouldReceive('microtime')->once()->andReturn(60.00);
		$this->db->shouldReceive('getQueryLog')->once()->andReturn(array('1', '2'));
		$this->log->shouldReceive('info')->with(
			PHP_EOL . 'Performance Statistics:' . PHP_EOL . 'Current Route: url' . PHP_EOL
			. 'Time to create the Response: 30000 ms' . PHP_EOL . 'Total performed DB Queries: 2' . PHP_EOL
		)->once();
		$helpers = $this->getHelpersInstance();

		$helpers->logPerformance();
	}

	/**
	 * @test forceSSL() can redirect to secure
	 */
	public function forceSSL_can_redirect_to_secure()
	{
		$this->request->shouldReceive('secure')->once()->andReturn(false);
		$this->request->shouldReceive('getRequestUri')->once()->andReturn('url');
		$this->redirect->shouldReceive('secure')->with('url')->once();
		$helpers = $this->getHelpersInstance();

		$helpers->forceSSL();
	}

	/**
	 * @test forceSSL() can return null
	 */
	public function forceSSL_can_return_null()
	{
		$this->request->shouldReceive('secure')->once()->andReturn(true);
		$this->redirect->shouldReceive('secure')->never();
		$helpers = $this->getHelpersInstance();

		$this->assertNull($helpers->forceSSL());
	}

	/**
	 * @test force404() can abort the app returning the backend 404 view
	 */
	public function force404_can_abort_the_app_returning_the_backend_404_view()
	{
		$this->baseController->shouldReceive('missingMethod')->with(array())->once()->andReturn('foo');
		$helpers = $this->getHelpersInstance();

		$this->assertEquals('foo', $helpers->force404());
	}

	/**
	 * @test redirectWithFlashMessage() redirects back per default
	 */
	public function redirectWithFlashMessage_redirects_back_per_default()
	{
		$this->session->shouldReceive('flash')->with('foo', 'bar')->once();
		$this->redirect->shouldReceive('back')->withNoArgs()->once()->andReturn('baz');
		$helpers = $this->getHelpersInstance();

		$this->assertEquals('baz', $helpers->redirectWithFlashMessage('foo', 'bar'));
	}

	/**
	 * @test redirectWithFlashMessage() can redirect to a given route
	 */
	public function redirectWithFlashMessage_can_redirect_to_a_given_route()
	{
		$this->session->shouldReceive('flash')->with('foo', 'bar')->once();
		$this->redirect->shouldReceive('route')->with('baz', array(), 302, array())->once()->andReturn('route');
		$helpers = $this->getHelpersInstance();

		$this->assertEquals('route', $helpers->redirectWithFlashMessage('foo', 'bar', 'baz'));
	}

}
