<?php namespace Larapress\Services;

use Illuminate\Config\Repository as Config;
use Illuminate\Translation\Translator as Lang;
use Illuminate\View\Environment as View;
use Monolog\Logger as Log;
use Illuminate\Http\Request;
use Illuminate\Session\Store as Session;
use Illuminate\Database\Connection as DB;
use Illuminate\Routing\Redirector as Redirect;
use BadMethodCallException;
use Illuminate\Http\RedirectResponse;
use Larapress\Controllers\BaseController;
use Larapress\Interfaces\HelpersInterface;
use Response;

class Helpers implements HelpersInterface
{

    /**
     * @var \Illuminate\Config\Repository
     */
    private $config;

    /**
     * @var \Illuminate\Translation\Translator
     */
    private $lang;

    /**
     * @var \Illuminate\View\Environment
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
	 * @param \Illuminate\Config\Repository $config
	 * @param \Illuminate\Translation\Translator $lang
	 * @param \Illuminate\View\Environment $view
	 * @param Mockably $mockably
	 * @param \Monolog\Logger $log
	 * @param \Illuminate\Http\Request $request
	 * @param \Illuminate\Session\Store $session
	 * @param \Illuminate\Database\Connection $db
	 * @param \Illuminate\Routing\Redirector $redirect
	 *
	 * @return \Larapress\Services\Helpers
	 */
    public function __construct(
        Config $config,
        Lang $lang,
        View $view,
        Mockably $mockably,
        Log $log,
        Request $request,
        Session $session,
        DB $db,
        Redirect $redirect
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

        switch ( $unit )
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

        return (int) $difference;
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
            . $this->getCurrentTimeDifference($this->session->get('start.time'), 'ms') . ' ms'
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
     * @return Response Returns a 404 Response with view
     */
    public function force404()
    {
        $controller = new BaseController;
        return $controller->missingMethod(array());
    }

}
