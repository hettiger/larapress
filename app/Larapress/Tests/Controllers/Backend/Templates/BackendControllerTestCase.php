<?php namespace Larapress\Tests\Controllers\Backend\Templates;

use Larapress\Tests\Controllers\Templates\ControllerTestCase;

class BackendControllerTestCase extends ControllerTestCase {

	public function setUp()
	{
		parent::setUp();

		$this->helpers->shouldReceive('initBaseController')->withNoArgs()->once();
	}

}
