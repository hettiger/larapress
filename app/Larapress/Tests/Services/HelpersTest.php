<?php namespace Larapress\Tests\Services;

use BadMethodCallException;
use Larapress\Services\Helpers;
use Mockery;
use Mockery\Mock;
use PHPUnit_Framework_TestCase;

class HelpersTest extends PHPUnit_Framework_TestCase
{

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

    public function setUp()
    {
        parent::setUp();

        $this->config = Mockery::mock('\Illuminate\Config\Repository');
        $this->lang = Mockery::mock('\Illuminate\Translation\Translator');
        $this->view = Mockery::mock('\Illuminate\View\Environment');
        $this->mockably = Mockery::mock('\Larapress\Services\Mockably');
        $this->log = Mockery::mock('\Monolog\Logger');
        $this->request = Mockery::mock('\Illuminate\Http\Request');
        $this->session = Mockery::mock('\Illuminate\Session\Store');
        $this->db = Mockery::mock('\Illuminate\Database\Connection');
        $this->redirect = Mockery::mock('\Illuminate\Routing\Redirector');
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
            $this->redirect
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers::setPageTitle() Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the Helpers::setPageTitle() method
    |
    */

    public function test_can_set_page_title()
    {
        $this->config->shouldReceive('get')->with('larapress.names.cms')->once()->andReturn('foo');
        $this->config->shouldReceive('offsetGet');
        $this->lang->shouldReceive('get')->with('larapress::general.bar')->once()->andReturn('bar');
        $this->view->shouldReceive('share')->with('title', 'foo | bar')->once();
        $helpers = $this->getHelpersInstance();

        $helpers->setPageTitle('bar');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers::getCurrentTimeDifference() Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the Helpers::getCurrentTimeDifference() method
    |
    */

    public function test_can_return_the_current_time_difference_in_minutes_per_default()
    {
        $this->mockably->shouldReceive('microtime')->once()->andReturn(60.00);
        $helpers = $this->getHelpersInstance();

        $e = 1;
        $a = $helpers->getCurrentTimeDifference(0.00);

        $this->assertEquals($e, $a);
    }

    public function test_can_return_the_current_time_difference_in_minutes_on_parameter()
    {
        $this->mockably->shouldReceive('microtime')->once()->andReturn(60.00);
        $helpers = $this->getHelpersInstance();

        $e = 1;
        $a = $helpers->getCurrentTimeDifference(0.00, 'm');

        $this->assertEquals($e, $a);
    }

    public function test_can_round_minutes_correctly()
    {
        $this->mockably->shouldReceive('microtime')->once()->andReturn(100.00);
        $helpers = $this->getHelpersInstance();

        $e = 1;
        $a = $helpers->getCurrentTimeDifference(0.00, 'm');

        $this->assertEquals($e, $a);
    }

    public function test_can_return_the_current_time_difference_in_seconds_on_parameter()
    {
        $this->mockably->shouldReceive('microtime')->once()->andReturn(60.00);
        $helpers = $this->getHelpersInstance();

        $e = 30;
        $a = $helpers->getCurrentTimeDifference(30.00, 's');

        $this->assertEquals($e, $a);
    }

    public function test_can_return_the_current_time_difference_in_milliseconds_on_parameter()
    {
        $this->mockably->shouldReceive('microtime')->once()->andReturn(60.00);
        $helpers = $this->getHelpersInstance();

        $e = 30000; // 1 second = 1000 milliseconds
        $a = $helpers->getCurrentTimeDifference(30.00, 'ms');

        $this->assertEquals($e, $a);
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function test_can_throw_a_bad_method_call_exception()
    {
        $this->mockably->shouldReceive('microtime')->once();
        $helpers = $this->getHelpersInstance();

        $helpers->getCurrentTimeDifference(microtime(true), 'default');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers::logPerformance() Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the Helpers::logPerformance() method
    |
    */

    public function test_can_log_the_applications_performance()
    {
        $this->log->shouldReceive('info')->once();
        $this->request->shouldReceive('getRequestUri')->once();
        $this->db->shouldReceive('getQueryLog')->once();
        $helpers = $this->getHelpersInstance();
        $this->session->shouldReceive('get')->with('start.time')->once();
        $this->mockably->shouldReceive('microtime')->withNoArgs()->once();

        $helpers->logPerformance();
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers::forceSSL() Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the Helpers::forceSSL() method
    |
    */

    public function test_can_force_ssl()
    {
        $this->request->shouldReceive('secure')->once()->andReturn(false);
        $this->request->shouldReceive('getRequestUri')->once()->andReturn('foo');
        $this->request->shouldReceive('root');
        $this->redirect->shouldReceive('secure')->with('foo')->once();
        $helpers = $this->getHelpersInstance();

        $helpers->forceSSL();
    }

    public function test_can_remain_silent()
    {
        $this->request->shouldReceive('secure')->once()->andReturn(true);
        $this->redirect->shouldReceive('secure')->never();
        $helpers = $this->getHelpersInstance();

        $this->assertNull($helpers->forceSSL());
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers::force404() Tests
    |--------------------------------------------------------------------------
    |
    | Here is where you can test the Helpers::force404() method
    |
    */

    public function test_can_abort_the_app_and_return_the_backend_404_view()
    {
        $helpers = $this->getHelpersInstance();

        $result = $helpers->force404();

        $this->assertInstanceOf('Illuminate\Http\Response', $result);
        $this->assertAttributeContains('larapress::errors.404', 'view', $result->getOriginalContent());
    }

}
