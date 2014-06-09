<?php namespace Larapress\Tests\Services\Proxies;

use Larapress\Services\Helpers;

class HelpersProxy extends Helpers {

	/**
	 * @param float $laravel_start
	 */
	public function setLaravelStart($laravel_start)
	{
		$this->laravel_start = $laravel_start;
	}

}
