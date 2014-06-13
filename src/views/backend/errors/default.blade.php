@extends('thor::backend.layout')
@section('main')
<div class="row error-page">
    <div class="col-lg-12">
        <h1 class="page-header al-c"><i class="block fa fa-3x fa-warning"></i> Unexpected Error</h1>
        <p class="lead al-c">The application cannot continue.</p>
        @if(Config::get('app.debug'))
        <div class="container well al-c">
        {{$exception->getMessage()}} in 
        <b>{{$exception->getFile()}}:{{$exception->getLine()}}</b>
        </div>
        @endif
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
@stop