@if ( Captcha::isRequired() )
    {{ HTML::style('larapress/assets/css/pages/captcha/captcha.css') }}

    {{ Form::open(array('onsubmit' => 'return larapress.formSubmit();')) }}
        {{ Form::captcha() }}
        {{ Form::submit(trans('forms.Verify that you are human'), array('id' => 'captcha-submit')) }}
    {{ Form::close() }}

    <div id="captcha-success">
        @lang('messages.Success'):
        @lang('messages.A captcha can\'t stop you. We wont ask again for a while now.')
    </div>
    <div id="captcha-failure">
        @lang('messages.Error'):
        @lang('messages.You didn\'t enter the correct captcha text. Please try again.')
    </div>

    <script type="text/javascript">
        var captcha_validation_url = '{{ $captcha_validation_url }}';
    </script>

    {{ HTML::script('larapress/assets/js/pages/captcha/captcha.js') }}
@endif
