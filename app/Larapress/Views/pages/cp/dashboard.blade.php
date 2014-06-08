@extends('larapress::layouts.default')

@section('content')
    <center>
        <h3>Congratulations – You're now logged in!</h3>
        {{ HTML::link('admin/logout', 'Logout') }}
    </center>
@stop
