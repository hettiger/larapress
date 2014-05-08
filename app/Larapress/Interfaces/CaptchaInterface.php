<?php namespace Larapress\Interfaces;

interface CaptchaInterface {

    /**
     * Shares the required data for the reCAPTCHA
     *
     * @return void
     */
    public function shareDataToViews();

}
