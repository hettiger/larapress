<?php namespace Larapress\Services;

use Illuminate\Config\Repository as Config;
use Illuminate\Session\Store as Session;
use Illuminate\View\Environment as View;
use Larapress\Interfaces\CaptchaInterface;

class Captcha implements CaptchaInterface {

	/**
	 * @var \Illuminate\View\Environment
	 */
	private $view;

	/**
	 * @var \Illuminate\Config\Repository
	 */
	private $config;

	/**
	 * @var \Illuminate\Session\Store
	 */
	private $session;

	/**
	 * @var Helpers
	 */
	private $helpers;

	/**
	 * @var Mockably
	 */
	private $mockably;

	/**
	 * @param View $view
	 * @param Config $config
	 * @param Session $session
	 * @param Helpers $helpers
	 * @param Mockably $mockably
	 *
	 * @return \Larapress\Services\Captcha
	 */
	public function __construct(View $view, Config $config, Session $session, Helpers $helpers, Mockably $mockably)
	{
		$this->view = $view;
		$this->config = $config;
		$this->session = $session;
		$this->helpers = $helpers;
		$this->mockably = $mockably;
	}

	/**
	 * Check if the reCAPTCHA is required
	 *
	 * @return bool Returns true if the captcha is required
	 */
	public function isRequired()
	{
		if (!$this->config->get('larapress.settings.captcha.active'))
		{
			return false;
		}

		$timer = $this->config->get('larapress.settings.captcha.timer');

		if ($this->helpers->getCurrentTimeDifference($this->session->get('captcha.passed.time', 0), 'm') >= $timer)
		{
			return true;
		}

		return false;
	}

	/**
	 * Shares the required data for the reCAPTCHA
	 *
	 * @return void
	 */
	public function shareDataToViews()
	{
		$this->view->share('captcha_validation_url', $this->mockably->route('larapress.api.captcha.validate.post'));
	}

}
