# Configuration

## Introduction

The configuration file for this CMS can be found here: `app/config/larapress.php`

> While you'll find some deeper information on the configuration entries here not everything will be documented here.
> The configuration file should be self explaining for the most parts.
> (It also comes with a good amount of comments ...)

__If you need help with the laravel configuration files have a look at the [project website](http://laravel.com).__

### Settings

#### ssl

__Be aware that setting this to `true` will only secure the backend routes.__

When you've set it to true you could use the filter `force.ssl` for the complete application too:

```php
// app/filters.php

/*
 * Search following line and customize it like shown below this comment block:
 * Route::when($backend_url . '*', 'force.ssl');
 */

Route::when('*', 'force.ssl');
```

> Remember: The `force.ssl` filter will only force SSL if you've set the `larapress.settings.ssl` configuration to `true`.

### Names

#### cms

If the name of the cms has to appear somewhere on a view we're reading out it's name from the configuration.
This ensures you could change the logos and this configuration entry if you didn't want a client to know which system you're using.

### URL Configuration

#### backend

Lets say you login to the CMS from `https://domain.tld/admin/login`.
Changing this configuration entry to `cms` for example would prospectively force you to browse to `https://domain.tld/cms/login` in order to login.

> Preferably you should decide what you set here once and leave it the way it is.
