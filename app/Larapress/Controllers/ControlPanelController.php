<?php namespace Larapress\Controllers;

use View;

class ControlPanelController extends BaseController
{

    public function getDashboard()
    {
        return View::make('larapress.pages.cp.dashboard');
    }

}
