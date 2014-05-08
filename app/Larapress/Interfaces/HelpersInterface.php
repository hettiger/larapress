<?php namespace Larapress\Interfaces;

use BadMethodCallException;
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
     * Get the time difference of a time record compared to the present
     *
     * @param float $time_record A record of microtime(true) in the past
     * @param string $unit Can be either 'ms', 's' or 'm' (milliseconds, seconds, minutes)
     * @return int Returns the time difference in the given unit
     * @throws BadMethodCallException
     */
    public function getCurrentTimeDifference($time_record, $unit = 'm');

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

}
