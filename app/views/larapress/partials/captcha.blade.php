@extends('larapress.layouts.default')

{{-- We're just extending and coding to a section here for testing purposes, will be removed on merge. --}}

@section('content')
    <style type="text/css">
        button#captcha-submit {
            width: 318px !important;
        }
    </style>

    {{ Form::captcha() }}
    {{ Form::button(trans('forms.Verify that you are human'), array('id' => 'captcha-submit')) }}
@stop
