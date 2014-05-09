@extends('larapress.layouts.default')

@section('head.extension')
    {{ HTML::style('larapress/assets/css/pages/errors/404.css') }}
@stop

@section('content')
    <div id="wrapper" class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="jumbotron">
                <h1>@lang('general.404 Error')</h1>
                <p>@lang('general.Sorry this page does not exist.')</p>
                <p>
                    {{ HTML::link('/', trans('general.Homepage'), array(
                        'class' => 'btn btn-primary btn-lg',
                        'role'  => 'button'
                    )) }}
                </p>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>
@stop
