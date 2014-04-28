@extends('larapress.layouts.master')

@section('head.extension')
    {{ HTML::style('larapress/assets/css/pages/home/login.css') }}
@stop

@section('body')
    <div id="wrapper">
        <div id="login-box">
            <img src="{{ asset('larapress/assets/png/logo.png') }}">
            @yield('content')
        </div>
    </div>
@stop
