<?php namespace Larapress\Services;

use BadMethodCallException;
use Illuminate\Config\Repository as Config;
use Illuminate\Database\Connection as DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector as Redirect;
use Illuminate\Session\Store as Session;
use Illuminate\Translation\Translator as Lang;
use Illuminate\View\Factory as View;
use Larapress\Interfaces\BaseControllerInterface;
use Larapress\Interfaces\HelpersInterface;
use Larapress\Interfaces\MockablyInterface;
use Monolog\Logger as Log;

class Helpers implements HelpersInterface {

	/**
	 * @var \Illuminate\Config\Repository
	 */
	private $config;

	/**
	 * @var \Illuminate\Translation\Translator
	 */
	private $lang;

	/**
	 * @var \Illuminate\View\Factory
	 */
	private $view;

	/**
	 * @var Mockably
	 */
	private $mockably;

	/**
	 * @var \Monolog\Logger
	 */
	private $log;

	/**
	 * @var \Illuminate\Http\Request
	 */
	private $request;

	/**
	 * @var \Illuminate\Session\Store
	 */
	private $session;

	/**
	 * @var \Illuminate\Database\Connection
	 */
	private $db;

	/**
	 * @var \Illuminate\Routing\Redirector
	 */
	private $redirect;

	/**
	 * @var \Larapress\Controllers\BaseController
	 */
	private $baseController;

	/**
	 * @var float
	 */
	protected $laravel_start = LARAVEL_START;

	/**
	 * @param \Illuminate\Config\Repository $config
	 * @param \Illuminate\Translation\Translator $lang
	 * @param \Illuminate\View\Factory $view
	 * @param \Larapress\Interfaces\MockablyInterface $mockably
	 * @param \Monolog\Logger $log
	 * @param \Illuminate\Http\Request $request
	 * @param \Illuminate\Session\Store $session
	 * @param \Illuminate\Database\Connection $db
	 * @param \Illuminate\Routing\Redirector $redirect
	 * @param \Larapress\Interfaces\BaseControllerInterface $baseController
	 *
	 * @return \Larapress\Services\Helpers
	 */
	public function __construct(
		Config $config,
		Lang $lang,
		View $view,
		MockablyInterface $mockably,
		Log $log,
		Request $request,
		Session $session,
		DB $db,
		Redirect $redirect,
		BaseControllerInterface $baseController
	) {
		$this->config = $config;
		$this->lang = $lang;
		$this->view = $view;
		$this->mockably = $mockably;
		$this->log = $log;
		$this->request = $request;
		$this->session = $session;
		$this->db = $db;
		$this->redirect = $redirect;
		$this->baseController = $baseController;
	}

	/**
	 * Sets the page title (Shares the title variable for the view)
	 *
	 * @param string $page_name The page name
	 * @return void
	 */
	public function setPageTitle($page_name)
	{
		$title = $this->config->get('larapress.names.cms') . ' | '
			. $this->lang->get('larapress::general.' . $page_name);

		$this->view->share('title', $title);
	}

	/**
	 * Get the time difference of a time record compared to the present
	 *
	 * @param float $time_record A record of microtime(true) in the past
	 * @param string $unit Can be either 'ms', 's' or 'm' (milliseconds, seconds, minutes)
	 * @return int Returns the time difference in the given unit
	 * @throws BadMethodCallException
	 */
	public function getCurrentTimeDifference($time_record, $unit = 'm')
	{
		$current_time = $this->mockably->microtime();

		switch ($unit)
		{
			case 'ms':
				$difference = round(($current_time - $time_record) * 1000);
				break;
			case 's':
				$difference = round($current_time - $time_record);
				break;
			case 'm':
				$difference = floor(round(($current_time - $time_record)) / 60);
				break;
			default:
				throw new BadMethodCallException;
		}

		return (int)$difference;
	}

	/**
	 * Writes performance related statistics into the log file
	 *
	 * @return void
	 */
	public function logPerformance()
	{
		$this->log->info(
			PHP_EOL . 'Performance Statistics:' . PHP_EOL .
			'Current Route: ' . $this->request->getRequestUri()
			. PHP_EOL .
			'Time to create the Response: '
			. $this->getCurrentTimeDifference($this->laravel_start, 'ms') . ' ms'
			. PHP_EOL .
			'Total performed DB Queries: ' . count($this->db->getQueryLog())
			. PHP_EOL
		);
	}

	/**
	 * Force to use https:// requests
	 *
	 * @return null|RedirectResponse Redirects to the https:// protocol if the current request is insecure
	 */
	public function forceSSL()
	{
		if ( ! $this->request->secure() )
		{
			return $this->redirect->secure($this->request->getRequestUri());
		}

		return null; // The request is already secure
	}

	/**
	 * Abort the app and return the backend 404 response
	 *
	 * @return \Illuminate\Http\Response Returns a 404 Response with view
	 */
	public function force404()
	{
		return $this->baseController->missingMethod(array());
	}

	/**
	 * Set a flash message and either redirect to a given route or the last page
	 *
	 * @param string $key The session flash message key
	 * @param string $message The session flash message value
	 * @param string|null $route The Route name to redirect to (can be left empty to just redirect back)
	 * @param array $parameters Parameters for the route (See the Laravel documentation)
	 * @param int $status Status code for the route (See the Laravel documentation)
	 * @param array $headers Headers for the route (See the Laravel documentation)
	 * @return \Illuminate\HTTP\RedirectResponse
	 */
	public function redirectWithFlashMessage(
		$key,
		$message,
		$route = null,
		$parameters = array(),
		$status = 302,
		$headers = array()
	) {
		$this->session->flash($key, $message);

		if ( ! is_null($route) )
		{
			return $this->redirect->route($route, $parameters = array(), $status = 302, $headers = array());
		}

		return $this->redirect->back();
	}

}
