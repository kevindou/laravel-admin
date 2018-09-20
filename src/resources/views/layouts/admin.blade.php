<?php
$user             = \Illuminate\Support\Facades\Auth::user();
$strUserAvatar    = data_get($user, 'avatar') ?: asset('admin-assets/img/avatar.png');
$strUserName      = data_get($user, 'name') ?: 'admin';
$strUserCreatedAt = data_get($user, 'created_at') ?: date('Y-m-d H:i:s');
?>
        <!DOCTYPE html>
<html lang="en">
<head>
    @include('admin::common.header')
    <style>
        #admin-nav > li.active > a {
            background-color: #f9f9f9;
            padding-bottom: 13px;
            border-bottom: 2px solid #00c0ef;
        }

        .navbar-top-links .dropdown-menu ul.admin-ul a {
            padding: 0 0 0 3px;
        }

        .admin-ul {
            display: inline-block;
            min-width: 160px;
            margin: 5px;
            padding: 0;
            list-style: none;
            zoom: 1;
        }

        .admin-ul > li {
            height: 28px;
            line-height: 28px;
        }

        .admin-ul > li > a {
            padding-left: 5px;
            display: block;
            line-height: 28px;
            height: 28px;
            color: #373d41;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .admin-ul > li > a:hover {
            color: white;
            background: #00c0ef;
        }

        .admin-ul > li.first {
            padding-left: 5px;
            color: #999;
            background-color: #f0f0f0;
        }
    </style>
</head>
<body class=" hold-transition sidebar-mini skin-white">
<div class="wrapper">
    <!-- Main Header -->
    <header class="main-header">
        <!-- Logo -->
        <a href="{{ url('admin/index/index')  }}" class="logo">
            <span class="logo-mini"><b>{{ trans('admin.projectNameMini') }}</b></span>
            <span class="logo-lg"><b>{{ trans('admin.projectName') }}</b></span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle b-l" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
        @includeIf('admin::common.top_nav')
        <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    @if (config('admin.messages-menu'))
                        <li class="dropdown messages-menu">
                            <!-- Menu toggle button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-envelope-o"></i>
                                <span class="label label-success">4</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">You have 4 messages</li>
                                <li>
                                    <!-- inner menu: contains the messages -->
                                    <ul class="menu">
                                        <li><!-- start message -->
                                            <a href="#">
                                                <div class="pull-left">
                                                    <!-- User Image -->
                                                    <img src=" http://www.gravatar.com/avatar/77eddd4a460eb5af5db6b7911926f7f5.jpg?s=80&amp;d=mm&amp;r=g "
                                                         class="img-circle" alt="User Image"/>
                                                </div>
                                                <!-- Message title and timestamp -->
                                                <h4>
                                                    Support Team
                                                    <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                                </h4>
                                                <!-- The message -->
                                                <p>Why not buy a new awesome theme?</p>
                                            </a>
                                        </li><!-- end message -->
                                    </ul><!-- /.menu -->
                                </li>
                                <li class="footer"><a href="#">See All Messages</a></li>
                            </ul>
                        </li>
                    @endif
                    @if (config('admin.notifications-menu'))
                    <!-- Notifications Menu -->
                        <li class="dropdown notifications-menu">
                            <!-- Menu toggle button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-bell-o"></i>
                                <span class="label label-warning">10</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">You have 10 notifications</li>
                                <li>
                                    <!-- Inner Menu: contains the notifications -->
                                    <ul class="menu">
                                        <li><!-- start notification -->
                                            <a href="#">
                                                <i class="fa fa-users text-aqua"></i> 5 new members joined today
                                            </a>
                                        </li><!-- end notification -->
                                    </ul>
                                </li>
                                <li class="footer"><a href="#">View all</a></li>
                            </ul>
                        </li>
                    @endif
                    @if (config('admin.tasks-menu'))
                        <li class="dropdown tasks-menu">
                            <!-- Menu Toggle Button -->
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-flag-o"></i>
                                <span class="label label-danger">9</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">You have 9 tasks</li>
                                <li>
                                    <!-- Inner menu: contains the tasks -->
                                    <ul class="menu">
                                        <li><!-- Task item -->
                                            <a href="#">
                                                <!-- Task title and progress text -->
                                                <h3>
                                                    Design some buttons
                                                    <small class="pull-right">20%</small>
                                                </h3>
                                                <!-- The progress bar -->
                                                <div class="progress xs">
                                                    <!-- Change the css width attribute to simulate progress -->
                                                    <div class="progress-bar progress-bar-aqua" style="width: 20%"
                                                         role="progressbar" aria-valuenow="20" aria-valuemin="0"
                                                         aria-valuemax="100">
                                                        <span class="sr-only">20% Complete</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li><!-- end task item -->
                                    </ul>
                                </li>
                                <li class="footer">
                                    <a href="#">View all tasks</a>
                                </li>
                            </ul>
                        </li>
                    @endif
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            <img src="{{ $strUserAvatar }}"
                                 class="user-image" alt="User Image"/>
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs">{{ $strUserName }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                <img src="{{ $strUserAvatar }}"
                                     class="img-circle" alt="User Image"/>
                                <p>
                                    {{ $strUserName }}
                                    <small>{{ $strUserCreatedAt }}</small>
                                </p>
                            </li>
                            @if (config('admin.user_schedule_events'))
                                <li class="user-body">
                                    <div class="col-xs-6 text-center mb10">

                                        <a href="{{ url('admin/calendars/self')  }}">
                                            <i class="fa fa-calendar"></i>
                                            <span>{{trans('admin.selfCalendars')}}</span>
                                        </a>
                                    </div>
                                </li>
                            @endif
                            <li class="user-footer">
                                @if (config('admin.user_detail'))
                                    <div class="pull-left">
                                        <a href="{{ url('admin/users/show') }}"
                                           class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                @endif
                                <div class="pull-right">
                                    <a href="{{ url('admin/login/logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"
                                       class="btn btn-default btn-flat">
                                        {{ trans('admin.logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ url('admin/login/logout') }}" method="POST"
                                          style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </li>

                    @if (config('admin.comments'))
                        <li>
                            <a href="#" data-toggle="control-sidebar">
                                <i class="fa fa-comments-o"></i>
                                <span class="label label-warning">10</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
    </header>
    <aside class="main-sidebar">
        <section class="sidebar">
            @if (config('admin.admin_left'))
                <div class="user-panel">
                    <div class="pull-left image">
                        <img src="{{ $strUserAvatar }}" class="img-circle" alt="User Image"/>
                    </div>
                    <div class="pull-left info">
                        <p>{{ $strUserName }}</p>
                        <!-- Status -->
                        <a href="#"><i class="fa fa-circle text-success"></i> {{ trans('admin.online') }}</a>
                    </div>
                </div>
            @endif
            @include('admin::common.menu')
        </section>
    </aside>
    <div class="content-wrapper">
        @include('admin::common.breadcrumbs')
        <section class="content ">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-ban"></i> {{trans('admin.alert')}} </h4>
                    {{ session('error') }}
                </div>
            @endif
            @yield('main-content')
        </section>
    </div>
    @if (config('admin.comments'))
        @include('admin::common.aside')
    @endif
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            {{ trans('admin.poweredBy') }} <a target="_blank" href="https://github.com/myloveGy"> liujinxing </a>
        </div>
        <strong>Copyright &copy; 2016</strong>
    </footer>
</div>
@include('admin::common.js')
<!-- AdminLTE App -->
<script src="{{ asset('admin-assets/js/app.min.js') }}"></script>
<script src="{{ asset('admin-assets/plugins/stickytabs/jquery.stickytabs.js') }}"></script>
<script src="{{ asset('admin-assets/plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('admin-assets/plugins/layer/layer.js') }}"></script>
<script src="{{ asset('admin-assets/laravel-admin/tools.min.js') }}"></script>
@stack('script')
<script>
    var strCurrentUrl = strCurrentUrl || $("#admin-menus").data("url") || "/";
    var $li = $("li[data-url='" + strCurrentUrl + "']").addClass("active");
    $li.parents(".treeview-menu").addClass("menu-open");
    $li.parents("li").addClass("active");
    // 后台菜单处理
    $(".admin-top-nav>li").hover(function () {
        $(this).addClass("open")
    }, function () {
        $(this).removeClass("open")
    });
</script>
</body>
</html>

