<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Captcha Test</title>

    <style type="text/css">
        button#captcha-submit {
            width: 318px !important;
        }

        span#captcha-success {
            display: none;
            color: #008000;
        }

        span#captcha-failure {
            display: none;
            color: #ff0000;
        }
    </style>
</head>
<body>

{{ Form::captcha() }}
{{ Form::token() }}
{{ Form::button(trans('forms.Verify that you are human'), array('id' => 'captcha-submit')) }}

{{ HTML::script('larapress/assets/js/pages/captcha/captcha.js') }}

<span id="captcha-success">Sucess</span>
<span id="captcha-failure">Failure</span>

</body>
</html>
