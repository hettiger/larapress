# Contribution Guidelines

## Setup for Development

Clone this repository and setup your development environment configuration:

1. Duplicate the file `.env.example.php` and name it `.env.local.php`
2. Apply your configuration
3. Make sure you never commit this file. (It should be git ignored per default)

__Important: Have a look at the docs directory. Else you'll be missing some important instructions like for our [taskrunner](docs/taskrunner.md).__

### Set the environment to local

Larapress is set to run in the production environment by default. To take control over the environment you can do the following:

1. Create a new file called `.env_name.php` in the app root
2. Make it return the environment you want to apply for the cli as well as the browser
3. Make sure you never commit this file. (It should be git ignored per default)

```php
<?php

// This is the complete content of the .env_name.php file

return 'local';
```

The example above will obviously change the environment to local. (which is the default development environment we use)

### Run following code in your command line:

```bash
composer install --dev -o
php artisan ide-helper:generate
chmod -R 777 app/storage
php artisan larapress:install
```

### Adding Packages

Whenever you add a `ServiceProvider` that is only needed for development tasks make sure you __don't__ add it in the `app/config/app.php` configuration file. Instead add it in the `app/config/local/app.php` file.

## Workflow

We're utilizing the [Git Flow](https://www.atlassian.com/de/git/workflows#!workflow-gitflow) with following prefixes:

- feature/*
- release/*
- hotfix/*

## Version Tags

We're prefixing version numbers with the letter "v" like `v1.0.1` and follow the [Semantic Versioning Principles](http://semver.org).

## Coding Style

We're following the [PSR-2 Coding Style Guide](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md).

## Development Environment

Don't apply changes that affect these instructions without updating them!

## Language

The complete codebase including docs must be written in english. This allows us hiring experts from all over the world when needed.

## Documentation

Pull Requests missing documentation won't be accepted.

## TDD

Pull Requests missing tests won't be accepted.
