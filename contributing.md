# Contribution Guidelines

## Setup for Development

Clone this repository and setup your development environment configuration:

1. Duplicate the file `.env.example.php` and name it `.env.local.php`
2. Apply your configuration
3. Make sure you never commit this file. (It should be git ignored per default)

### Set the environment to local

Currently the app is configured to use either the variable `$_ENV['APP_ENV']` to set the environment or fall back to `production`. To make it short: The whole Application runs in the `production` environment if you don't apply the following changes.

- For Apache: Use `SetEnv APP_ENV local` in your vhost configuration or `.htaccess` file.
- For nginx with php-fpm: Use `env[APP_ENV] = local` in the `php-fpm.conf` file. (Usually located at `/etc/php5/fpm/php-fpm.conf`)
- For nginx with php-cgi: Use `fastcgi_param APP_ENV local;` in your site configuration. (Context: location)
- Run `export APP_ENV="local"` in the command line to temporarily set the environment or add it to your `~/.bash_profile` for a permanent setup.

If you set the environment in the command line you could run into trouble trying to install the production version on your system. You can always check the currently set environment using `echo $APP_ENV` in the command line. Even if you've set a permanent environment in your `~/.bash_profile` you could just temporarily change it running `export APP_ENV="production"` for example.

### Run following code in your command line:

```bash
composer install --dev -o
php artisan ide-helper:generate
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
