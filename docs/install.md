# Installing larapress

Make sure you have installed composer. If you need help with that visit the [projects website](https://getcomposer.org).

Clone this repository and run following commands from the project root in your terminal:

```bash
composer install --no-dev -o
chmod -R 777 app/storage
php artisan larapress:install
```

Further instructions will be printed by the terminal.
