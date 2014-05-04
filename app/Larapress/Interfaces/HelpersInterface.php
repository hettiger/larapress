<?php namespace Larapress\Interfaces;

use Redirect;
use Response;

interface HelpersInterface {

    /**
     * Sets the page title (Shares the title variable for the view)
     *
     * @param string $page_name The page name
     * @return void
     */
    public function setPageTitle($page_name);

    /**
     * Writes performance related statistics into the log file
     *
     * @return void
     */
    public function logPerformance();

    /**
     * Force to use https:// requests
     *
     * @return null|Redirect Redirects to the https:// protocol if the current request is insecure
     */
    public function forceSSL();

    /**
     * Abort the app and return the backend 404 response
     *
     * @return Response Returns a 404 Response with view
     */
    public function force404();

    /**
     * Shares the required api url for the reCAPTCHA validation JavaScript
     *
     * @return void
     */
    public function shareCaptchaValidationUrl();

}
