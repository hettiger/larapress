@extends('larapress.layouts.default')

@section('content')
<center>
    <h1>404 Error</h1>
    <p>{{ HTML::link('/', 'Home') }}</p>
    <p>{{ HTML::link('admin/login', 'Backend Login') }}</p>
</center>
@stop
