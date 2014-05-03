<?php namespace Larapress\Controllers\Api;

use Controller;
use Helpers;
use Response;

class BaseApiController extends Controller
{

    /**
     * Missing Method
     *
     * Abort the app and return a 404 response
     *
     * @param array $parameters
     * @return Response
     */
    public function missingMethod($parameters = array())
    {
        Helpers::setPageTitle('404 Error');

        return Response::view('larapress.errors.404', array(), 404);
    }

}
