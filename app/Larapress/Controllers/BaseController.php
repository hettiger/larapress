<?php namespace Larapress\Controllers;

use App;
use Carbon\Carbon;
use Controller;
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

}
