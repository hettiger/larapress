# Themes

Larapress is using it's own namespaced views directory. The registration of the namespace is taking place in
`app/start/global.php` and is customisable. If you want to create your own user interface for the backend just go ahead
and duplicate the whole directory `app/Larapress/Views` to `app/cp-theme` for example. After you've done that change
 the registered directory of the larapress view namespace:

 ```php
 // app/start/global.php

 /*
  * Search following line and customize it like shown below this comment block:
  * View::addNamespace('larapress', __DIR__ . '/../Larapress/Views');
  */

 View::addNamespace('larapress', __DIR__ . '/../cp-theme');
 ```

 Done you can now apply your changes in `app/cp-theme` without the risk of breaking something.
