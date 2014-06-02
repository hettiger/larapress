<?php namespace Larapress\Interfaces;

use Illuminate\Session\Store as Session;
use Illuminate\View\Environment as View;
use Illuminate\Config\Repository as Config;
use Larapress\Services\Helpers;
use Larapress\Services\Mockably;

interface CaptchaInterface {

	/**
	 * @param View $view
	 * @param Config $config
	 * @param Session $session
	 * @param Helpers $helpers
	 * @param Mockably $mockably
	 *
	 * @return \Larapress\Interfaces\CaptchaInterface
	 */
	public function __construct(View $view, Config $config, Session $session, Helpers $helpers, Mockably $mockably);

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
