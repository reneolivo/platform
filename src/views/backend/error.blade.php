@extends('thor::backend.layout')
@section('main')
<div class="row">
    <div class="col-lg-12">
        @if(Input::get('code')=='403')
        <h1 class="page-header al-c"><i class="fa fa-lock"></i> Not Allowed</h1>
        <p class="lead al-c">Oops, it seems that don't have enough privileges to perform this operation</p>
        @else
        <h1 class="page-header al-c">Error 404</h1>
        <p class="lead al-c">Page Not Found</p>
        @endif
    </div><!-- /.col-lg-12 -->
</div><!-- /.row -->
@stop