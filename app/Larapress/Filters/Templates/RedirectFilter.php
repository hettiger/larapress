<?php namespace Larapress\Filters\Templates;

use Whoops\Exception\ErrorException;

abstract class RedirectFilter {

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

		$this->helpers = $app['Larapress\Interfaces\HelpersInterface'];
		$this->init($app);
	}

	/**
	 * Add more dependencies here if needed
	 *
	 * @param $app
	 * @return void
	 */
	protected function init($app) {}

	/**
	 * This method must redirect or return false
	 *
	 * @throws \Whoops\Exception\ErrorException
	 * @return \Illuminate\HTTP\RedirectResponse|bool
	 * @codeCoverageIgnore
	 */
	protected function redirect()
	{
		throw new ErrorException('A redirect filter must override the redirect method!');
	}

	/**
	 * See if a redirect is required and do so or return null
	 *
	 * @return \Illuminate\HTTP\RedirectResponse|null
	 */
	public function filter()
	{
		return $this->redirect() ? : null;
	}
	
}
