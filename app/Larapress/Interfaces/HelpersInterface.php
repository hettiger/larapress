<?php namespace Larapress\Interfaces;

use BadMethodCallException;
use Illuminate\Config\Repository as Config;
use Illuminate\Database\Connection as DB;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector as Redirect;
use Illuminate\Session\Store as Session;
use Illuminate\Translation\Translator as Lang;
use Illuminate\View\Environment as View;
use Monolog\Logger as Log;

interface HelpersInterface {

	/**
	 * @param \Illuminate\Config\Repository $config
	 * @param \Illuminate\Translation\Translator $lang
	 * @param \Illuminate\View\Environment $view
	 * @param \Larapress\Interfaces\MockablyInterface $mockably
	 * @param \Monolog\Logger $log
	 * @param \Illuminate\Http\Request $request
	 * @param \Illuminate\Session\Store $session
	 * @param \Illuminate\Database\Connection $db
	 * @param \Illuminate\Routing\Redirector $redirect
	 * @param \Larapress\Interfaces\BaseControllerInterface $baseController
	 *
	 * @return \Larapress\Interfaces\HelpersInterface
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
	);

	/**
	 * Sets the page title (Shares the title variable for the view)
	 *
	 * @param string $page_name The page name
	 * @return void
	 */
	public function setPageTitle($page_name);

	/**
	 * Get the time difference of a time record compared to the present
	 *
	 * @param float $time_record A record of microtime(true) in the past
	 * @param string $unit Can be either 'ms', 's' or 'm' (milliseconds, seconds, minutes)
	 * @return int Returns the time difference in the given unit
	 * @throws BadMethodCallException
	 */
	public function getCurrentTimeDifference($time_record, $unit = 'm');

	/**
	 * Writes performance related statistics into the log file
	 *
	 * @return void
	 */
	public function logPerformance();

	/**
	 * Force to use https:// requests
	 *
	 * @return null|Redirect Redirects to the https:// protocol if the current request is insecure
	 */
	public function forceSSL();

	/**
	 * Abort the app and return the backend 404 response
	 *
	 * @return \Illuminate\Http\Response Returns a 404 Response with view
	 */
	public function force404();

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
	);

}
