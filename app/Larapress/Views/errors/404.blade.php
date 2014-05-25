@extends('larapress::layouts.default')

@section('head.extension')
    {{ HTML::style('larapress/assets/css/pages/errors/404.css') }}
@stop

@section('content')
    <div class="jumbotron">
        <h1>@lang('larapress::general.404 Error')</h1>
        <p>@lang('larapress::general.This page does not exist.')</p>
        <p>
            {{ HTML::link('/', trans('larapress::general.Homepage'), array(
                'class' => 'btn btn-primary btn-lg',
                'role'  => 'button'
            )) }}
        </p>
    </div>
@stop
