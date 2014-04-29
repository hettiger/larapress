<?php namespace Larapress\Services;

use Config;
use DB;
use Lang;
use Larapress\Interfaces\HelpersInterface;
use Log;
use Redirect;
use Request;
use Session;
use View;

class Helpers implements HelpersInterface
{

    /**
     * Sets the page title (Shares the title variable for the view)
     *
     * @param string $page_name The page name
     * @return void
     */
    public function setPageTitle($page_name)
    {
        $title = Config::get('larapress.names.cms') . ' | ' . Lang::get('general.' . $page_name);
        View::share('title', $title);
    }

    /**
     * Get the time it took to create the response in ms
     *
     * @return float Returns the time in ms
     */
    protected function responseCreationTime()
    {
        $starting_time = Session::get('start.time');
        $current_time = microtime(true);

        $creation_time = round(($current_time - $starting_time) * 1000, 2);

        return $creation_time;
    }

    /**
     * Writes performance related statistics into the log file
     *
     * @return void
     */
    public function logPerformance()
    {
        Log::info(
            PHP_EOL . 'Performance Statistics:' . PHP_EOL .
            'Current Route: ' . Request::getRequestUri()
            . PHP_EOL .
            'Time to create the Response: ' . $this->responseCreationTime() . ' ms'
            . PHP_EOL .
            'Total performed DB Queries: ' . count(DB::getQueryLog())
            . PHP_EOL
        );
    }

    /**
     * Force to use https:// requests
     *
     * @return null|Redirect Redirects to the https:// protocol if the current request is insecure
     */
    public function forceSSL()
    {
        if ( ! Request::secure() )
        {
            return Redirect::secure(Request::getRequestUri());
        }

        return null; // The request is already secure
    }

}
