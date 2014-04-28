@extends('larapress.layouts.login')

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
