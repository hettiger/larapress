<?php namespace Larapress\Tests\Filters\Backend\Proxies;

use Larapress\Filters\Backend\ForceSSLFilter;

class ForceSSLFilterProxy extends ForceSSLFilter {

	public function __construct($config, $helpers)
	{
		$this->config = $config;
		$this->helpers = $helpers;
	}

}
