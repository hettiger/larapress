<?php namespace Larapress\Services;

use BadMethodCallException;
use Config;
use DB;
use Lang;
use Larapress\Controllers\BaseController;
use Larapress\Interfaces\HelpersInterface;
use Log;
use Redirect;
use Request;
use Response;
use Mockably as MockablyService;
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
     * Get the time difference of a time record compared to the present
     *
     * @param float $time_record A record of microtime(true) in the past
     * @param string $unit Can be either 'ms', 's' or 'm' (milliseconds, seconds, minutes)
     * @return int Returns the time difference in the given unit
     * @throws BadMethodCallException
     */
    public function getCurrentTimeDifference($time_record, $unit = 'm')
    {
        $current_time = MockablyService::microtime();

        switch ( $unit )
        {
            case 'ms':
                $difference = round(($current_time - $time_record) * 1000);
                break;
            case 's':
                $difference = round($current_time - $time_record);
                break;
            case 'm':
                $difference = floor(round(($current_time - $time_record)) / 60);
                break;
            default:
                throw new BadMethodCallException;
        }

        return (int) $difference;
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
            'Time to create the Response: '
            . $this->getCurrentTimeDifference(Session::get('start.time'), 'ms') . ' ms'
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

    /**
     * Abort the app and return the backend 404 response
     *
     * @return Response Returns a 404 Response with view
     */
    public function force404()
    {
        $controller = new BaseController;
        return $controller->missingMethod(array());
    }

}
