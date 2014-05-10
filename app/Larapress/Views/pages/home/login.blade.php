@extends('larapress::layouts.login')

@section('content')
    {{ Form::open() }}
        <div class="form-group">
            {{ Form::label('email', trans('forms.Email address')) }}
            {{ Form::text('email', '', array('class' => 'form-control', 'placeholder' => trans('forms.Enter email'))) }}
        </div>
        <div class="form-group">
            {{ Form::label('password', trans('forms.Password')) }}
            <a href="{{ route('larapress.home.reset.password.get') }}">
                <span class="badge"
                      data-toggle="tooltip"
                      data-placement="right"
                      title="@lang('general.Click to reset your password')">?
                </span>
            </a>
            {{ Form::password('password', array('class' => 'form-control', 'placeholder' => trans('forms.Password'))) }}
        </div>
        {{ Form::submit(trans('forms.Login'), array('class' => 'btn btn-lg btn-default btn-block btn-dark')) }}
    {{ Form::close() }}
@stop

@section('bottom.extension')
    <script type="application/x-javascript">
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@stop
