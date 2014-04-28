@extends('larapress.layouts.login')

@section('head.extension')
    <style type="text/css">
        body {
            background-color: #000000;
            background-image: radial-gradient(#ffffff, rgba(255,255,255,.2) 2px, transparent 40px), radial-gradient(#ffffff, rgba(255,255,255,.15) 1px, transparent 30px), radial-gradient(#ffffff, rgba(255,255,255,.1) 2px, transparent 40px), radial-gradient(rgba(255,255,255,.4), rgba(255,255,255,.1) 2px, transparent 30px);
            background-size: 550px 550px, 350px 350px, 250px 250px, 150px 150px;
            background-position: 0 0, 40px 60px, 130px 270px, 70px 100px;
        }

        #wrapper {
            margin: auto;
            padding: 8em 2em 0 2em;
            max-width: 459px;
        }

        #login-box {
            background-color: #ffffff;
            margin-top: 1.5em;
            padding: 1em;
            border: 1px solid #cccccc;
            border-radius: 6px;
        }

        img {
            max-width: 100%;
            margin: 0 0 2em 0;
        }

        .btn-dark {
            background-color: #383838;
            color: #fafafa;
        }

        .btn-dark:hover, .btn-dark:active {
            background-color: #2d2e2d;
            color: #fafafa;
        }
    </style>
@stop

@section('content')
    <form class="form-signin" role="form">
        <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
        </div>
        <button class="btn btn-lg btn-default btn-block btn-dark" type="submit">Sign in</button>
    </form>
@stop
