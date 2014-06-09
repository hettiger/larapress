<?php namespace Larapress\Controllers;

use Illuminate\Routing\Controller;
use Larapress\Interfaces\HelpersInterface as Helpers;

abstract class BaseController extends Controller {

	/**
	 * @var \Larapress\Interfaces\HelpersInterface
	 */
	protected $helpers;

	function __construct(Helpers $helpers)
	{
		$this->helpers = $helpers;
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
