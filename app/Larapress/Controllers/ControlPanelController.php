<?php namespace Larapress\Controllers;

use Helpers;
use View;

class ControlPanelController extends BaseController
{

    /**
     * Dashboard
     *
     * Loads the dashboard view which is the first thing you'll see after logging in.
     *
     * @return View
     */
    public function getDashboard()
    {
        Helpers::setPageTitle('Dashboard');

        return View::make('larapress::pages.cp.dashboard');
    }

}
