<?php namespace Larapress\Services;

use Larapress\Interfaces\MockablyInterface;

/*
|--------------------------------------------------------------------------
| Mockably
|--------------------------------------------------------------------------
|
| Here is where you can add wrappers for php functions so they are easily
| mockable by Mockably::shouldReceive()
|
*/

class Mockably implements MockablyInterface
{

    /**
     * Return current Unix timestamp in seconds with microseconds as float
     *
     * @return float
     */
    public function microtime()
    {
        return microtime(true);
    }

}
