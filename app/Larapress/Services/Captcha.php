<?php namespace Larapress\Services;

use Config;
use Helpers as HelpersService;
use Larapress\Interfaces\CaptchaInterface;
use Session;
use View;

class Captcha implements CaptchaInterface
{

    /**
     * Check if the reCAPTCHA is required
     *
     * @return bool Returns true if the captcha is required
     */
    public function isRequired()
    {
        if ( ! Config::get('larapress.settings.captcha.active') )
        {
            return false;
        }

        $timer = Config::get('larapress.settings.captcha.timer');

        if ( HelpersService::getCurrentTimeDifference(Session::get('captcha.passed.time', 0), 'm') >= $timer )
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
        View::share('captcha_validation_url', route('larapress.api.captcha.validate.post'));
    }

}
