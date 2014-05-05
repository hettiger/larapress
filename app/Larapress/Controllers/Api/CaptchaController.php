<?php namespace Larapress\Controllers\Api;

use Input;
use Response;
use Validator;

class CaptchaController extends BaseController
{

    public function postValidate()
    {
        $validator = Validator::make(
            Input::all(),
            array('recaptcha_response_field' => 'required|recaptcha')
        );

        if ( $validator->fails() )
        {
            return Response::json(array('result' => 'failed'));
        }

        return Response::json(array('result' => 'success'));
    }

}
