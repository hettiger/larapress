<?php namespace Larapress\Services;

use Config;
use Helpers as HelpersService;
use Larapress\Interfaces\CaptchaInterface;
use Session;
use View;

class Captcha implements CaptchaInterface
{

    /**
     * Shares the required data for the reCAPTCHA
     *
     * @return void
     */
    public function shareDataToViews()
    {
        View::share('captcha_validation_url', route('larapress.api.captcha.validate.post'));

        $timer = Config::get('larapress.settings.captcha.timer');

        if ( HelpersService::getCurrentTimeDifference(Session::get('captcha.passed.time', 0), 'm') >= $timer )
        {
            View::share('captcha_required', true);
        }
        else
        {
            View::share('captcha_required', false);
        }
    }

}
