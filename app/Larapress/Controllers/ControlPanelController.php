<?php namespace Larapress\Controllers;

use Helpers;
use View;

class ControlPanelController extends BaseController
{

    public function getDashboard()
    {
        Helpers::setPageTitle('Dashboard');

        return View::make('larapress.pages.cp.dashboard');
    }

}
