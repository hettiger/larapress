<?php namespace Larapress\Controllers;

use Larapress\Interfaces\HelpersInterface as Helpers;
use Illuminate\View\Factory as View;

class ControlPanelController extends BaseController {

	/**
	 * @var \View
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
	 * @return View
	 */
	public function getDashboard()
	{
		$this->helpers->setPageTitle('Dashboard');

		return $this->view->make('larapress::pages.cp.dashboard');
	}

}
