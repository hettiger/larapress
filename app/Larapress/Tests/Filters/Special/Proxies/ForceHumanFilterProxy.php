<?php namespace Larapress\Tests\Filters\Special\Proxies;

use Larapress\Filters\Special\ForceHumanFilter;

class ForceHumanFilterProxy extends ForceHumanFilter {

	public function __construct($captcha, $helpers)
	{
		$this->captcha = $captcha;
		$this->helpers = $helpers;
	}

}
