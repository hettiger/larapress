<?php namespace Larapress\Controllers;

use App;
use Config;
use Controller;
use View;

class BaseController extends Controller
{

    function __construct()
    {
        $lang = App::getLocale();
        $title = Config::get('larapress.names.cms') . ' | ' . trans('general.Login');

        View::share('lang', $lang);
        View::share('title', $title);
    }

}
