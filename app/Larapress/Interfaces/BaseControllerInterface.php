<?php namespace Larapress\Interfaces;

use Illuminate\Http\Response;

interface BaseControllerInterface {

	/**
	 * Missing Method
	 *
	 * Abort the app and return a 404 response
	 *
	 * @param array $parameters
	 * @return Response
	 */
	public function missingMethod($parameters = array());

}
