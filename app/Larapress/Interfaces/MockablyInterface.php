<?php namespace Larapress\Interfaces;

interface MockablyInterface {

    /**
     * Return current Unix timestamp with microseconds as float
     *
     * @return float
     */
    public function microtime();

}
