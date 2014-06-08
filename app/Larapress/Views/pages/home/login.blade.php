@extends('larapress::layouts.login')

@section('content')
    {{ Form::open() }}
        <div class="form-group">
            {{ Form::label('email', trans('larapress::forms.Email address')) }}
            {{ Form::text('email', '', array('class' => 'form-control', 'placeholder' => trans('larapress::forms.Enter email'))) }}
        </div>
        <div class="form-group">
            {{ Form::label('password', trans('larapress::forms.Password')) }}
            <a href="{{ route('larapress.home.reset.password.get') }}">
                <span class="badge"
                      data-toggle="tooltip"
                      data-placement="right"
                      title="@lang('larapress::general.Click to reset your password')">?
                </span>
            </a>
            {{ Form::password('password', array('class' => 'form-control', 'placeholder' => trans('larapress::forms.Password'))) }}
        </div>
        {{ Form::submit(trans('larapress::forms.Login'), array('class' => 'btn btn-lg btn-default btn-block btn-dark')) }}
    {{ Form::close() }}
@stop

@section('bottom.extension')
    <script type="application/x-javascript">
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@stop
