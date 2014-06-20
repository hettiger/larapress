<?php namespace Larapress\Tests\Controllers\Backend;

use Larapress\Controllers\Backend\ControlPanelController;
use Larapress\Tests\Controllers\Backend\Templates\BackendControllerTestCase;
use Mockery;
use Mockery\Mock;

class ControlPanelControllerTest extends BackendControllerTestCase {

	/**
	 * @var Mock
	 */
	private $view;

	public function setUp()
	{
		parent::setUp();

		$this->view = Mockery::mock('\Illuminate\View\Factory');
	}

	protected function getControlPanelControllerInstance()
	{
		return new ControlPanelController($this->helpers, $this->view);
	}

	/**
	 * @test getDashboard() sets the page title and makes the dashboard view
	 */
	public function getDashboard_sets_the_page_title_and_makes_the_dashboard_view()
	{
		$this->helpers->shouldReceive('setPageTitle')->with('Dashboard')->once();
		$this->view->shouldReceive('make')->with('larapress::pages.cp.dashboard')->once()->andReturn('foo');
		$controller = $this->getControlPanelControllerInstance();

		$this->assertEquals('foo', $controller->getDashboard());
	}

}
