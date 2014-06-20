<?php namespace Larapress\Controllers\Backend;

use Larapress\Controllers\BaseController;
use Larapress\Interfaces\HelpersInterface as Helpers;

abstract class BackendBaseController extends BaseController {

	public function __construct(Helpers $helpers)
	{
		parent::__construct($helpers);

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

}
