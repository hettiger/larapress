<?php namespace Larapress\Filters\Backend;

use Larapress\Filters\Templates\RedirectFilter;

class ForceSSLFilter extends RedirectFilter {

	/**
	 * @var \Illuminate\Config\Repository
	 */
	protected $config;

	/**
	 * @codeCoverageIgnore
	 */
	protected function init($app)
	{
		$this->config = $app['config'];
	}

	/**
	 * Force SSl if it's enabled by the configuration
	 *
	 * @return bool|\Illuminate\HTTP\RedirectResponse|null
	 */
	protected function redirect()
	{
		if ( $this->config->get('larapress.settings.ssl') )
		{
			return $this->helpers->forceSSL();
		}

		return false;
	}

}
