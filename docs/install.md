# Installing larapress

__Warning: These instructions are for the production build__

> You might want to have a look at `CONTRIBUTING.md` if you want to run the development build.

Make sure you have installed composer. If you need help with that visit the [projects website](https://getcomposer.org).

Run following commands from the project root in your terminal:

```bash
composer create --no-dev larapress-cms/larapress my-project-name
chmod -R 777 app/storage
```

1. Duplicate the file `.env.example.php` and name it `.env.php`
2. Apply your configuration in `.env.php`
3. If you want to use MySQL you'll also need to change the default database connection in `app/config/database.php`

__Example for 3.__

```php
// app/config/database.php

/*
 * Change following line like shown below this comment block:
 * 'default' => 'sqlite',
 */

'default' => 'mysql',
```

__Finally install larapress from the project root in your terminal:__

```bash
php artisan larapress:install
```

Further instructions will be printed by the terminal.
