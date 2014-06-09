<?php namespace Larapress\Controllers;

use Controller;
use Larapress\Interfaces\HelpersInterface as Helpers;

class BaseController extends Controller {

	/**
	 * @var \Larapress\Interfaces\HelpersInterface
	 */
	protected $helpers;

	function __construct(Helpers $helpers)
	{
		$this->helpers = $helpers;

		$this->init();
	}

	/**
	 * Initialize the base controller sharing important data to all views
	 *
	 * @return void
	 */
	protected function init()
	{
		$this->helpers->initBaseController();
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
