<!DOCTYPE html>
<html lang="{{{ $lang }}}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{{ $title }}}</title>

    {{-- Bootstrap --}}
    {{ HTML::style('larapress/assets/css/larapress.css') }}

    @include('larapress::partials.head')

    {{-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries --}}
    <!--[if lt IE 9]>
        {{ HTML::script('larapress/assets/js/fallback.js') }}
    <![endif]-->
</head>
<body>

<noscript id="larapress-noscript">
    {{ trans('general.The control panel requires JavaScript support. '
    . 'Here are the '
    . '<a href="http://www.enable-javascript.com/" target="_blank">'
    . 'instructions how to enable JavaScript in your web browser</a>.') }}
</noscript>

@yield('body')

{{-- Include all compiled plugins below (including jQuery) --}}
{{ HTML::script('larapress/assets/js/larapress.js') }}

@yield('bottom.extension')

</body>
</html>
