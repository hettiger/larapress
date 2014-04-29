<?php namespace Larapress\Services;

use Config;
use Lang;
use Larapress\Interfaces\HelpersInterface;
use View;

class Helpers implements HelpersInterface
{

    /**
     * Sets the page title (Shares the title variable for the view)
     *
     * @param string $page_name The page name
     * @return void
     */
    public function setPageTitle($page_name)
    {
        $title = Config::get('larapress.names.cms') . ' | ' . Lang::get('general.' . $page_name);
        View::share('title', $title);
    }

}
