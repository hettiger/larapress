@if ( Captcha::isRequired() )
    {{ HTML::style('larapress/assets/css/pages/captcha/captcha.css') }}

    <div id="captcha-success" class="alert alert-success alert-dismissable fade in">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <strong>@lang('larapress::messages.Success')!</strong>
        @lang('larapress::messages.A captcha can\'t stop you. We wont ask again for a while now.')
    </div>

    <div id="captcha-failure" class="alert alert-danger alert-dismissable fade in">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <strong>@lang('larapress::messages.Error')!</strong>
        @lang('larapress::messages.You didn\'t enter the correct captcha text. Please try again.')
    </div>

    <div id="captcha-wrapper">
        <p>
            <strong>@lang('larapress::forms.Please verify that you are human first.')</strong>
        </p>

        {{ Form::open(array(
            'id'       => 'captcha-form',
            'onsubmit' => 'return larapress.formSubmit();'
        )) }}
            {{ Form::captcha() }}
            {{ Form::submit(trans('larapress::forms.Verify that you are human'), array(
                'id'    => 'captcha-submit',
                'class' => 'btn btn-danger btn-xs'
            )) }}
        {{ Form::close() }}
    </div>

    <script type="text/javascript">
        var captcha_validation_url = '{{ $captcha_validation_url }}';
    </script>

    {{ HTML::script('larapress/assets/js/pages/captcha/captcha.js') }}
@endif
