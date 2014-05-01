<?php

namespace Larapress\Facades;

use Illuminate\Support\Facades\Facade;

class Narrator extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'narrator'; }

}
