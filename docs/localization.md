# Localization

Larapress is using it's own namespaced lang directory. The registration of the namespace is taking place in
`app/start/global.php` and is customisable. If you want to apply translations just go ahead and duplicate the whole 
directory `app/Larapress/Lang` to `app/lang-larapress` for example. After you've done that change the registered 
directory of the larapress lang namespace:

```php
// app/start/global.php

/*
* Search following line and customize it like shown below this comment block:
* Lang::addNamespace('larapress', __DIR__ . '/../Larapress/Lang');
*/

Lang::addNamespace('larapress', __DIR__ . '/../lang-larapress');
```

Done you can now apply your changes in `app/lang-larapress` without the risk of breaking something.
