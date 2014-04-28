<?php namespace Larapress\Controllers;

use Helpers;
use View;

class ControlPanelController extends BaseController
{

    public function getDashboard()
    {
        Helpers::set_page_title('Dashboard');

        return View::make('larapress.pages.cp.dashboard');
    }

}
