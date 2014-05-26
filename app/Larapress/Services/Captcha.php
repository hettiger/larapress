<?php namespace Larapress\Services;

use Larapress\Interfaces\CaptchaInterface;
use Illuminate\Session\Store as Session;
use Illuminate\View\Environment as View;
use Illuminate\Config\Repository as Config;

class Captcha implements CaptchaInterface
{

    /**
     * @var \Illuminate\View\Environment
     */
    protected $view;

    /**
     * @var \Illuminate\Config\Repository
     */
    protected $config;

    /**
     * @var \Illuminate\Session\Store
     */
    protected $session;

    /**
     * @var Helpers
     */
    protected $helpers;

    /**
     * @param View $view
     * @param Config $config
     * @param Session $session
     * @param Helpers $helpers
     * 
     * @return void
     */
    public function __construct(View $view, Config $config, Session $session, Helpers $helpers)
    {
        $this->view = $view;
        $this->config = $config;
        $this->session = $session;
        $this->helpers = $helpers;
    }

    /**
     * Check if the reCAPTCHA is required
     *
     * @return bool Returns true if the captcha is required
     */
    public function isRequired()
    {
        if ( ! $this->config->get('larapress.settings.captcha.active') )
        {
            return false;
        }

        $timer = $this->config->get('larapress.settings.captcha.timer');

        if ( $this->helpers->getCurrentTimeDifference($this->session->get('captcha.passed.time', 0), 'm') >= $timer )
        {
            return true;
        }

        return false;
    }

    /**
     * Shares the required data for the reCAPTCHA
     *
     * @return void
     */
    public function shareDataToViews()
    {
        $this->view->share('captcha_validation_url', route('larapress.api.captcha.validate.post'));
    }

}
