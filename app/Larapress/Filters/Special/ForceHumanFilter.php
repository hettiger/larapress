<?php namespace Larapress\Filters\Special;

use Illuminate\Http\RedirectResponse;

class ForceHumanFilter {

	/**
	 * @var \Larapress\Interfaces\CaptchaInterface
	 */
	protected $captcha;

	/**
	 * @var \Illuminate\Session\Store
	 */
	protected $session;

	/**
	 * @var \Illuminate\Routing\Redirector
	 */
	protected $redirect;

	/**
	 * @codeCoverageIgnore
	 */
	public function __construct()
	{
		$app = app();

		$this->captcha = $app['captcha'];
		$this->session = $app['session.store'];
		$this->redirect = $app['redirect'];
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
			$this->session->flash('error', 'Please verify that you are human first.');

			return $this->redirect->back();
		}

		return null; // Captcha is not required, proceed
	}

}
