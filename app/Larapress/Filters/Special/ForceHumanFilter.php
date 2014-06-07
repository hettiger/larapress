<?php namespace Larapress\Filters\Special;

use Illuminate\Http\RedirectResponse;

class ForceHumanFilter {

	/**
	 * @var \Larapress\Interfaces\CaptchaInterface
	 */
	protected $captcha;

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

		$this->captcha = $app['captcha'];
		$this->helpers = $app['helpers'];
	}

	/**
	 * Check if the user must pass a captcha first
	 * Redirect to the last route with a flash message if he needs to
	 *
	 * @return RedirectResponse|null
	 */
	public function filter()
	{
		if ( $this->captcha->isRequired() )
		{
			return $this->helpers->redirectWithFlashMessage('error', 'Please verify that you are human first.');
		}

		return null; // Captcha is not required, proceed
	}

}
