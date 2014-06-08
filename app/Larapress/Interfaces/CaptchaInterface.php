<?php namespace Larapress\Interfaces;

use Illuminate\Config\Repository as Config;
use Illuminate\Session\Store as Session;
use Illuminate\View\Factory as View;

interface CaptchaInterface {

	/**
	 * @param View $view
	 * @param Config $config
	 * @param Session $session
	 * @param \Larapress\Interfaces\HelpersInterface $helpers
	 * @param \Larapress\Interfaces\MockablyInterface $mockably
	 *
	 * @return \Larapress\Interfaces\CaptchaInterface
	 */
	public function __construct(
		View $view,
		Config $config,
		Session $session,
		HelpersInterface $helpers,
		MockablyInterface $mockably
	);

	/**
	 * Check if the reCAPTCHA is required
	 *
	 * @return bool Returns true if the captcha is required
	 */
	public function isRequired();

	/**
	 * Shares the required data for the reCAPTCHA
	 *
	 * @return void
	 */
	public function shareDataToViews();

}
