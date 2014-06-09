<?php namespace Larapress\Controllers;

use Carbon\Carbon;
use Controller;
use Illuminate\Foundation\Application as App;
use Illuminate\View\Factory as View;
use Larapress\Interfaces\HelpersInterface as Helpers;

class BaseController extends Controller {

	/**
	 * @var \Illuminate\Foundation\Application
	 */
	private $app;

	/**
	 * @var \Carbon\Carbon
	 */
	private $carbon;

	/**
	 * @var \Illuminate\View\Factory
	 */
	private $view;

	/**
	 * @var \Larapress\Interfaces\HelpersInterface
	 */
	private $helpers;

	function __construct(App $app, Carbon $carbon, View $view, Helpers $helpers)
	{
		$this->app = $app;
		$this->carbon = $carbon;
		$this->view = $view;
		$this->helpers = $helpers;

		$this->init();
	}

	protected function init()
	{
		$lang = $this->app->getLocale();
		$now = $this->carbon->now();

		$this->view->share('lang', $lang);
		$this->view->share('now', $now);
	}

	/**
	 * Missing Method
	 *
	 * Abort the app and return a 404 response
	 *
	 * @param array $parameters
	 * @return \Illuminate\Http\Response
	 */
	public function missingMethod($parameters = array())
	{
		return $this->helpers->force404();
	}

}
