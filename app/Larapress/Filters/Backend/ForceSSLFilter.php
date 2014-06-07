<?php namespace Larapress\Filters\Backend;

class ForceSSLFilter {

	/**
	 * @var \Illuminate\Config\Repository
	 */
	protected $config;

	/**
	 * @var \Larapress\Interfaces\HelpersInterface
	 */
	protected $helpers;

	/**
	 * @codeCoverageIgnore
	 */
	public function __construct()
	{
		$app = app();

		$this->config = $app['config'];
		$this->helpers = $app['helpers'];
	}

	/**
	 * Look into the configuration and either force a ssl connection by redirecting or return null
	 *
	 * @return null|\Illuminate\Routing\Redirector
	 */
	public function filter()
	{
		if ( $this->config->get('larapress.settings.ssl') )
		{
			return $this->helpers->forceSSL();
		}

		return null; // SSL is not enabled
	}

}
