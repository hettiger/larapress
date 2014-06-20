<?php namespace Larapress\Tests\Controllers\Backend\Proxies;

use Larapress\Controllers\Backend\HomeController;

class HomeControllerProxy extends HomeController {

	public function getErrorMessages()
	{
		return $this->error_messages;
	}

	public function resetPasswordFixture($exception)
	{
		return parent::resetPasswordFixture($exception);
	}

}
