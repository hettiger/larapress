# Introduction

## Directory structure

If you have a look at `app/Larapress` you'll see that larapress is trying to stand out of your way.   
Here's a list for you where we made changes to the default laravel installation:

* app/Larapress/*
* app/assets/*
* app/lang/*/email.php
* app/lang/*/forms.php
* app/lang/*/general.php
* app/lang/*/messages.php
* app/start/artisan.php
* app/start/global.php
* app/filters.php
* bootstrap/start.php
* docs/*
* public/larapress/*
* public/favicon.ico

> You might want to compare the app root yourself ;)

Have a look into the directories and I'm sure you'll get a better idea of what's going on under the hood.   
(There are lots of comments ...)

## PHPUnit Tests

As you've seen we've introduced a second tests directory.    
To run our tests you'll need to use our configuration:

`larapress.phpunit.xml`
