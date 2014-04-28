<?php namespace Larapress\Controllers;

use App;
use Carbon\Carbon;
use Config;
use Controller;
use View;

class BaseController extends Controller
{

    function __construct()
    {
        $lang = App::getLocale();
        $title = Config::get('larapress.names.cms') . ' | ' . trans('general.Login');
        $now = Carbon::now();

        View::share('lang', $lang);
        View::share('title', $title);
        View::share('now', $now);
    }

}
