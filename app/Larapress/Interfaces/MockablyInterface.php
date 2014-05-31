<?php namespace Larapress\Interfaces;

interface MockablyInterface {

    /**
     * Return current Unix timestamp in seconds with microseconds as float
     *
     * @return float
     */
    public function microtime();

	/**
	 * Return a random string of given length
	 *
	 * @param int $length
	 * @return string
	 */
	public function str_random($length);

}
