<?php namespace Larapress\Tests\Filters\Special\Proxies;

use Larapress\Filters\Special\ForceHumanFilter;

class ForceHumanFilterProxy extends ForceHumanFilter {

	public function __construct($captcha, $session, $redirect)
	{
		$this->captcha = $captcha;
		$this->session = $session;
		$this->redirect = $redirect;
	}

}
