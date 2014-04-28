@extends('larapress.layouts.login')

@section('content')
    {{ Form::open() }}
        <div class="form-group">
            {{ Form::label('email', trans('forms.Email address')) }}
            {{ Form::text('email', '', array('class' => 'form-control', 'placeholder' => trans('forms.Enter email'))) }}
        </div>
        <div class="form-group">
            {{ Form::label('password', trans('forms.Password')) }}
            {{ Form::password('password', array('class' => 'form-control', 'placeholder' => trans('forms.Password'))) }}
        </div>
        {{ Form::submit(trans('forms.Sign in'), array('class' => 'btn btn-lg btn-default btn-block btn-dark')) }}
    {{ Form::close() }}
@stop
