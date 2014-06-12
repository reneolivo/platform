<div class="sidebar navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav" id="side-menu">
            <li @if(Route::is('backend.home')) class="active"@endif>
                <a class="active" href="{{Backend::url()}}"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
            </li>
            @if(Sentinel::can('list_users'))
            <li @if(Backend::requestIs('users')) class="active"@endif>
                <a href="{{Backend::url('users')}}"><i class="fa fa-user fa-fw"></i> Users</a>
            </li>
            @endif
            @if(Sentinel::can('list_roles'))
            <li @if(Backend::requestIs('roles')) class="active"@endif>
                <a href="{{Backend::url('roles')}}"><i class="fa fa-group fa-fw"></i> Roles</a>
            </li>
            @endif
            @if(Sentinel::can('list_permissions'))
            <li @if(Backend::requestIs('permissions')) class="active"@endif>
                <a href="{{Backend::url('permissions')}}"><i class="fa fa-lock fa-fw"></i> Permissions</a>
            </li>
            @endif
            @if(Sentinel::can('list_languages'))
            <li @if(Backend::requestIs('languages')) class="active"@endif>
                <a href="{{Backend::url('languages')}}"><i class="fa fa-flag fa-fw"></i> Languages</a>
            </li>
            @endif
            @yield('sidemenu_append')
        </ul><!-- /#side-menu -->
        <small class="thorcms-version al-c label label-info">Thor CMS {{THORCMS_VERSION}}{{'@'.App::environment()}}</small>
        @yield('sidebar_append')
    </div><!-- /.sidebar-collapse -->
</div>