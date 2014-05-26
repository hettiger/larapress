<?php namespace Larapress\Interfaces;

use Illuminate\Session\Store as Session;
use Illuminate\View\Environment as View;
use Illuminate\Config\Repository as Config;
use Larapress\Services\Helpers;

interface CaptchaInterface {

    /**
     * @param View $view
     * @param Config $config
     * @param Session $session
     * @param Helpers $helpers
     *
     * @return void
     */
    public function __construct(View $view, Config $config, Session $session, Helpers $helpers);

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
