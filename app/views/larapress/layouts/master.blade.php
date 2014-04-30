<!DOCTYPE html>
<html lang="{{{ $lang }}}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{{ $title }}}</title>

    {{-- Bootstrap --}}
    {{ HTML::style('larapress/assets/css/larapress.css') }}

    @include('larapress.partials.head')

    {{-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries --}}
    <!--[if lt IE 9]>
        {{ HTML::script('larapress/assets/js/fallback.js') }}
    <![endif]-->
</head>
<body>

@yield('body')

{{-- Include all compiled plugins below (including jQuery) --}}
{{ HTML::script('larapress/assets/js/larapress.js') }}

@yield('bottom.extension')

</body>
</html>
