# Filters

## CSRF Protection

We decided to apply the `csrf` filter to all `POST`, `PUT`, `PATCH` and `DELETE` requests.  
This has been done in `app/filters.php` on the `CSRF Protection Filter` section.

Be careful if you're planning to change this behaviour.  
The whole backend relies on this pattern filter.

You could do something like this if you need to:

```php
// app/filters.php

$backend_url = Config::get('larapress.urls.backend');

Route::when($backend_url . '*', 'csrf', array('post', 'put', 'patch', 'delete'));
```

## Special larapress Filters

Have a look at `app/Larapress/filters.php`.
There are some special filters that could be useful in your application.
Use these for any routes you could need them for.
