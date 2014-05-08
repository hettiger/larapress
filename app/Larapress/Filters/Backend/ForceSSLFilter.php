<?php namespace Larapress\Filters\Backend;

use Config;
use Helpers;
use Redirect;

class ForceSSLFilter
{

    /**
     * Look into the configuration and either force a ssl connection by redirecting or return null
     *
     * @return null|Redirect
     */
    public function filter()
    {
        if ( Config::get('larapress.settings.ssl') )
        {
            return Helpers::forceSSL();
        }

        return null; // SSL is not enabled
    }

}
