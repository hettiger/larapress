<?php namespace Larapress\Controllers\Backend;

use Larapress\Interfaces\HelpersInterface as Helpers;
use Illuminate\View\Factory as View;

class ControlPanelController extends BackendBaseController {

	/**
	 * @var \Illuminate\View\Factory
	 */
	private $view;

	public function __construct(Helpers $helpers, View $view)
	{
		parent::__construct($helpers);

		$this->view = $view;
	}

	/**
	 * Dashboard
	 *
	 * Loads the dashboard view which is the first thing you'll see after logging in.
	 *
	 * @return \Illuminate\View\View
	 */
	public function getDashboard()
	{
		$this->helpers->setPageTitle('Dashboard');

		return $this->view->make('larapress::pages.cp.dashboard');
	}

}
