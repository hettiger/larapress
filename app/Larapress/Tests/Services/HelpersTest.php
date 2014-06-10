<?php namespace Larapress\Tests\Services;

use BadMethodCallException;
use Larapress\Tests\Services\Proxies\HelpersProxy;
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
	private $response;

	/**
	 * @var Mock
	 */
	private $app;

	/**
	 * @var Mock
	 */
	private $carbon;

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
		$this->response = Mockery::mock('\Illuminate\Support\Facades\Response');
		$this->app = Mockery::mock('\Illuminate\Foundation\Application');
		$this->carbon = Mockery::mock('\Carbon\Carbon');
	}

	public function tearDown()
	{
		parent::tearDown();

		Mockery::close();
	}

	protected function getHelpersInstance()
	{
		return new HelpersProxy(
			$this->config,
			$this->lang,
			$this->view,
			$this->mockably,
			$this->log,
			$this->request,
			$this->session,
			$this->db,
			$this->redirect,
			$this->response,
			$this->app,
			$this->carbon
		);
	}

	protected function setPageTitleFixture($page_name)
	{
		$this->config->shouldReceive('get')->with('larapress.names.cms')->once()->andReturn('foo');
		$this->lang->shouldReceive('get')->with('larapress::general.' . $page_name)->once()->andReturn($page_name);
		$this->view->shouldReceive('share')->with('title', 'foo | ' . $page_name)->once();
	}

	/**
	 * @test initBaseController() can share important data to all views
	 */
	public function initBaseController_can_share_important_data_to_all_views()
	{
		$this->app->shouldReceive('getLocale')->withNoArgs()->atLeast()->once()->andReturn('foo');
		$this->carbon->shouldReceive('now')->withNoArgs()->atLeast()->once()->andReturn('bar');
		$this->view->shouldReceive('share')->with('lang', 'foo')->atLeast()->once();
		$this->view->shouldReceive('share')->with('now', 'bar')->atLeast()->once();
		$helpers = $this->getHelpersInstance();

		$helpers->initBaseController();
	}

	/**
	 * @test setPageTitle()
	 */
	public function setPageTitle()
	{
		$this->setPageTitleFixture('bar');
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
		$this->mockably->shouldReceive('microtime')->once()->andReturn(60.00);
		$this->db->shouldReceive('getQueryLog')->once()->andReturn(array('1', '2'));
		$this->log->shouldReceive('info')->with(
			PHP_EOL . 'Performance Statistics:' . PHP_EOL . 'Current Route: url' . PHP_EOL
			. 'Time to create the Response: 30000 ms' . PHP_EOL . 'Total performed DB Queries: 2' . PHP_EOL
		)->once();
		$helpers = $this->getHelpersInstance();
		$helpers->setLaravelStart(30.00);

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
		$this->setPageTitleFixture('404 Error');
		$this->response->shouldReceive('view')->with('larapress::errors.404', array(), 404)->once()->andReturn('foo');
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

	/**
	 * @test handleMultipleExceptions() can return the correct error message
	 */
	public function handleMultipleExceptions_can_return_the_correct_error_message()
	{
		$error_messages = array('Exception' => 'foo');
		$helpers = $this->getHelpersInstance();

		$error_message = $helpers->handleMultipleExceptions(new \Exception(), $error_messages);

		$this->assertEquals('foo', $error_message);
	}

	/**
	 * @test handleMultipleExceptions() can log and rethrow the exception when missing the adequate message
	 * @expectedException \Exception
	 * @expectedExceptionMessage bar
	 */
	public function handleMultipleExceptions_can_log_and_rethrow_the_exception_when_missing_the_adequate_message()
	{
		$error_messages = array('SomeException' => 'foo');
 		$this->log->shouldReceive('error')
			->with('Unhandled Exception rethrown. See the Stacktrace below for more information:')->once();
		$helpers = $this->getHelpersInstance();

		$helpers->handleMultipleExceptions(new \Exception('bar'), $error_messages);
	}

}
