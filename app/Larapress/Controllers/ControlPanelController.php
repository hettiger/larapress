<?php namespace Larapress\Controllers;

class ControlPanelController extends BaseController
{

    public function getDashboard()
    {
        return (string) \Sentry::check();
    }

}
