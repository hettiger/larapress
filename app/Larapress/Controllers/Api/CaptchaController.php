<?php namespace Larapress\Controllers\Api;

use Illuminate\Http\Request as Input;
use Illuminate\Session\Store as Session;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Factory as Validator;
use Larapress\Interfaces\MockablyInterface as Mockably;

class CaptchaController extends BaseController {

	/**
	 * @var \Illuminate\Validation\Factory
	 */
	private $validator;

	/**
	 * @var \Illuminate\Http\Request
	 */
	private $input;

	/**
	 * @var \Illuminate\Support\Facades\Response
	 */
	private $response;

	/**
	 * @var \Illuminate\Session\Store
	 */
	private $session;

	/**
	 * @var \Larapress\Interfaces\MockablyInterface
	 */
	private $mockably;

	public function __construct(
		Validator $validator,
		Input $input,
		Response $response,
		Session $session,
		Mockably $mockably
	) {
		$this->validator = $validator;
		$this->input = $input;
		$this->response = $response;
		$this->session = $session;
		$this->mockably = $mockably;
	}

	public function postValidate()
	{
		$validator = $this->validator->make(
			$this->input->all(),
			array('recaptcha_response_field' => 'required|recaptcha')
		);

		if ( $validator->fails() )
		{
			return $this->response->json(array('result' => 'failed'));
		}

		$this->session->put('captcha.passed.time', $this->mockably->microtime());

		return $this->response->json(array('result' => 'success'));
	}

}
