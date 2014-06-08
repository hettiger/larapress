<?php namespace Larapress\Services;

use Larapress\Interfaces\MockablyInterface;

/*
|--------------------------------------------------------------------------
| Mockably
|--------------------------------------------------------------------------
|
| Here is where you can add wrappers for functions so they are easily
| mockable by Mockably::shouldReceive()
|
*/

class Mockably implements MockablyInterface {

	/**
	 * Return current Unix timestamp in seconds with microseconds as float
	 *
	 * @return float
	 */
	public function microtime()
	{
		return microtime(true);
	}

	/**
	 * Return a random string of given length
	 *
	 * @param int $length
	 * @return string
	 * @codeCoverageIgnore (Laravel function)
	 */
	public function str_random($length)
	{
		return str_random($length);
	}

	/**
	 * Generate a URL to a named route.
	 *
	 * @param string $string
	 * @param array $parameters
	 * @return string
	 * @codeCoverageIgnore (Laravel function)
	 */
	public function route($string, $parameters = array())
	{
		return route($string, $parameters);
	}

	/**
	 * Stop executing the app and echo out some message
	 *
	 * @param string $message
	 * @codeCoverageIgnore (Survive!)
	 */
	public function mockable_die($message = '')
	{
		die($message);
	}

}
