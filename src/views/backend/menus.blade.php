<nav class="backend-top-navbar navbar navbar-inverse navbar-fixed-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <div class="navbar-brand">
            <a class="navbar-brand-back" href="{{lang_url()}}" title="Main site"><i class="fa fa-angle-double-left"></i></a>
            <a class="navbar-brand-title" href="{{Backend::url()}}"><?php echo Backend::config('title') ?> <small>{{'@'.App::environment()}}</small></a>
        </div>
    </div>
    <!-- /.navbar-header -->

    <ul class="nav navbar-top-links navbar-right">
        @yield('navbar_prepend')
        <!-- /.dropdown -->
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-globe"></i> {{Lang::language()->name}} <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li class="dropdown-header">Change language:</li>
                @foreach(Lang::getAvailableLocales() as $code => $locale)
                <li @if($code == Lang::code())class="active"@endif><a href="{{URL::langSwitch($code)}}">{{$code}}</a></li>
                @endforeach
            </ul>
        </li>
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-user fa-fw"></i> {{Auth::user()->username}}  <i class="fa fa-caret-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user">
                <li><a href="{{Backend::url('users/'.Auth::user()->id)}}"><i class="fa fa-pencil-square-o fa-fw"></i> Account</a></li>
                <li class="divider"></li>
                <li><a href="{{Backend::url('logout')}}"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
            </ul>
            <!-- /.dropdown-user -->
        </li>
        <!-- /.dropdown -->
    </ul>
    <!-- /.navbar-top-links -->

    @include('thor::backend.sidebar')
    <!-- /.navbar-static-side -->
</nav>