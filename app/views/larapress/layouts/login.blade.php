@extends('larapress.layouts.master')

@section('body')
    <div id="wrapper">
        <div id="login-box">
            <img src="{{ asset('larapress/assets/png/logo.png') }}">
            @yield('content')
        </div>
    </div>
@stop
