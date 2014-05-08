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

## App::before & App::after

We're taking care of throttling and performance logging in these filters.  
We highly suggest you leave this the way it is. If you're planning on making some custom additions ...  
There are comments, study them ;-)

> If you don't like the performance logging you should disable it in the configuration.  
> We really suggest not touching this stuff. You can find the configuration file here:  
> `app/config/larapress.php`  
> As you can see it's already disabled because you're looking at the production configuration file.  
> If you still want to disable this have a look at `app/config/local/larapress.php`.

## Special larapress Filters

Use these filters for any routes you could need them for.     

__Anyways:__    
You better don't touch this stuff unless you find a bug.    
You can easily leverage the complete security arrangement here.

## Filters for the larapress backend

You better don't touch this stuff unless you find a bug.    
You can easily leverage the complete security arrangement here.

## Pattern Filters for the larapress backend

You better don't touch this stuff unless you find a bug.    
You can easily leverage the complete security arrangement here.
