<?php namespace Larapress\Interfaces;

interface HelpersInterface {

    /**
     * Sets the page title (Shares the title variable for the view)
     *
     * @param string $page_name The page name
     * @return void
     */
    public function set_page_title($page_name);

}
