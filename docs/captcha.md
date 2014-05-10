# Captcha

## Introduction

There may be times when you need a captcha on your website. Well larapress wont change your abilities to implement some
custom captcha. Anyways it's providing you a reCAPTCHA implementation that can be plugged in as easy as the power cable
of your computer.

### Briefing

* The captcha validation is accomplished by an ajax request so you can have multiple forms in one view without stress testing the user
* We used good old JavaScript without any frameworks to ensure compatibility to your dependencies
* Once the user passed the captcha he wont be asked to solve it for a given amount of time in minutes. (See `app/config/larapress.php`)
* Validation is handled by a before filter that redirects back to the last route with a flash message on validation errors.

### Why does larapress offer this feature?

Well we thought it might be a good idea securing the reset password form with a captcha. Making this part of larapress
reusable was just an improvement.

### Troubleshooting

> I don't like this at all ...

This is an opt out functionality. See `app/config/larapress.php`. Be aware that disabling will make the reset password
functionality less secure.

> I need to customize the time it takes until the passed captcha verification expires

Seriously ... you should have a look at `app/config/larapress.php` ;-)

> Input error: k: Format of site key was invalid

You need to setup your Google reCAPTCHA API Keys in `.env.*.php`

## Usage

### View (Step one)

At first you'll need to display the captcha to the user somehow right? Well larapress is taking care of you. Simply
include our partial in your view wherever you need it:

```php
// Your view

@include('larapress::partials.captcha')
```

Because the above included partial requires some data you'll need to add the following in your controller method or
route closure: `Captcha::shareDataToViews();`

__Example:__

```php
// app/routes.php

Route::get('/', function()
{
    Captcha::shareDataToViews();

    return View::make('hello');
});
```

You'll need some way to tell the user that he must pass the captcha first before accessing the route you'll secure in
the next step of this guide. Here's a example of how that could be accomplished:

```php
// Your view

@if ( Session::has('error') )
    <div class="alert alert-danger">
        <strong>@lang('messages.Error')!</strong>
        @lang('messages.' . Session::get('error'))
    </div>
@endif
```

As you see the `force.human` filter you'll apply in the next step will `Redirect::back()` with `Session::flash('error', 'message')`.

### Filter (Step two)

Apply the `force.human` before filter to routes that should be protected from being accessed by a robot. (e.g. the post
action of your contact form)

__Example__

```php
// app/filters.php

Route::when($backend_url . '/reset-password', 'force.human', array('post'));
```

### No JavaScript Fallback (Optional)

The larapress backend has it's own fallback, no worries about that ... Anyways if you integrate this captcha into your website you'll likely want to add a no JavaScript fallback. (JavaScript is required without further modifications ...)

## Customization

If you want to customize this in any way you can simple duplicate and edit the `larapress::partials.captcha` view.
You could use your own stylesheet or even replace the javascript with a custom solution ... Be creative ;-)

__If you run into trouble please have a look at `app/assets/larapress/js/pages/captcha/captcha.js` and
`app/assets/larapress/less/pages/captcha/captcha.less` before asking questions. I'm sure you'll get the idea.__
