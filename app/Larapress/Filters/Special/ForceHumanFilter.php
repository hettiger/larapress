<?php namespace Larapress\Filters\Special;

use Captcha;
use Illuminate\Http\RedirectResponse;
use Redirect;
use Session;

class ForceHumanFilter
{

    /**
     * Check if the user must pass a captcha first
     * Redirect to the last route with a flash message if he needs to
     *
     * @return RedirectResponse|null
     */
    public function filter()
    {
        if ( Captcha::isRequired() )
        {
            Session::flash('error', 'Please verify that you are human first.');
            return Redirect::back();
        }

        return null; // Captcha is not required, proceed
    }

}
