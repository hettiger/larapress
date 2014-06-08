<?php

/*
|--------------------------------------------------------------------------
| Register The Artisan Commands
|--------------------------------------------------------------------------
|
| Each available Artisan command must be registered with the console so
| that it is available to be called. We'll register every command so
| the console gets access to each of the command object instances.
|
*/

//

/*
|--------------------------------------------------------------------------
| Require The Larapress Artisan File
|--------------------------------------------------------------------------
|
| In order to keep larapress completely isolated from your application we
| require an extra artisan file here. This allows you updating larapress
| with no worries about breaking your code.
|
*/

require app_path().'/Larapress/artisan.php';
