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
		$this->captcha = $app['Larapress\Interfaces\CaptchaInterface'];
	}

	/**
	 * Redirect the user with a flash message if the captcha is required to be passed first
	 *
	 * @return bool|\Illuminate\HTTP\RedirectResponse
	 */
	protected function redirect()
	{
		if ( $this->captcha->isRequired() )
		{
			return $this->helpers->redirectWithFlashMessage('error', 'Please verify that you are human first.');
		}

		return false;
	}

}
