<?php namespace Larapress\Controllers;

use App;
use Carbon\Carbon;
use Controller;
use Helpers;
use Response;
use View;

class BaseController extends Controller
{

    function __construct()
    {
        $lang = App::getLocale();
        $now = Carbon::now();

        View::share('lang', $lang);
        View::share('now', $now);
    }

    /**
     * Missing Method
     *
     * Abort the app and return a 404 response
     *
     * @param array $parameters
     * @return Response
     */
    public function missingMethod($parameters = array())
    {
        Helpers::setPageTitle('404 Error');

        return Response::view('larapress::errors.404', array(), 404);
    }

}
