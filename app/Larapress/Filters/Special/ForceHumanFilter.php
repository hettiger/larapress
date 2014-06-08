<?php namespace Larapress\Filters\Special;

use Larapress\Filters\Templates\RedirectFilter;

class ForceHumanFilter extends RedirectFilter {

	/**
	 * @var \Larapress\Interfaces\CaptchaInterface
	 */
	protected $captcha;

	/**
	 * @codeCoverageIgnore
	 */
	protected function init($app)
	{
		$this->captcha = $app['captcha'];
	}

	protected function redirect()
	{
		if ( $this->captcha->isRequired() )
		{
			return $this->helpers->redirectWithFlashMessage('error', 'Please verify that you are human first.');
		}

		return false;
	}

}
