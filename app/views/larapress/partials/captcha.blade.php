<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Captcha Test</title>

    <style type="text/css">
        button#captcha-submit {
            width: 318px !important;
        }
    </style>
</head>
<body>

{{ Form::captcha() }}
{{ Form::button(trans('forms.Verify that you are human'), array('id' => 'captcha-submit')) }}


{{ HTML::script('larapress/assets/js/twix.js') }}
{{ HTML::script('larapress/assets/js/pages/captcha/captcha.js') }}

</body>
</html>
