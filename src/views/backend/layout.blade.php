<!doctype html><?php $unwrap = (isset($unwrap) and ($unwrap==true)); ?>
<html lang="en" class="{{implode(' ', $doc_classes)}} view-{{$doc_view_slug}} {{$unwrap ? 'unwrap' : ''}}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{Backend::config('title')}} | {{ucfirst($doc_view)}}</title>

        <meta name="author" content="Javier Aguilar, mjolnic.com">
        <meta name="robots" content="NOINDEX, NOFOLLOW">

        <link rel="shortcut icon" href="{{Backend::asset('img/logo.jpg')}}">

        <style type="text/css" id="relativecss">html,body{position:static}body *{position:relative}</style>
        <link href="{{Backend::asset('css/app.min.css')}}" rel="stylesheet">

        @yield('head_append')
    </head>
    <body>
        <?php if($unwrap === true) : ?>
        @yield('main')
        <?php else: ?>
        <div id="wrapper">
            @include('thor::backend.menus')
            <div id="page-wrapper">
                @include('thor::backend.alerts')
                @yield('main')
            </div><!-- /#page-wrapper -->
        </div><!-- /#wrapper -->
        <?php endif; ?>
        
        <script src="{{Backend::asset('js/app.min.js')}}"></script>
        @yield('body_append')
    </body>
</html>