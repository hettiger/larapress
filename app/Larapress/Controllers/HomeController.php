<?php namespace Larapress\Controllers;

use View;

class HomeController extends BaseController
{

    public function getLogin()
    {
        return View::make('larapress.pages.home.login');
    }

    public function postLogin()
    {
        return false;
    }

}
