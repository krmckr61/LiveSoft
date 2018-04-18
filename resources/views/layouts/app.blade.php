<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="{!! config('app.author') !!}">
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    <meta name="representationId" content="{!! Auth::user()->id !!}">
    <link rel="icon" type="image/png" sizes="16x16" href="{!! url('asset/plugins/images/favicon.png') !!}">
    <title>@yield('title') | {!! config('app.name') !!}</title>
    <!-- Bootstrap Core CSS -->
    <link href="{!! url('asset/bootstrap/dist/css/bootstrap.min.css') !!}" rel="stylesheet">
    <link href="{!! url('asset/plugins/bower_components/bootstrap-extension/css/bootstrap-extension.css') !!}"
          rel="stylesheet">
    <!-- Menu CSS -->
    <link href="{!! url('asset/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css') !!}" rel="stylesheet">
    <!-- animation CSS -->
    <link href="{!! url('asset/css/animate.css') !!}" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="{!! url('asset/css/style.css') !!}" rel="stylesheet">
    <!-- color CSS you can use different color css from css/colors folder -->
    <link href="{!! url('asset/css/colors/megna.css') !!}" id="theme" rel="stylesheet">
    <link href="{!! url('asset/plugins/bower_components/sweetalert/sweetalert.css') !!}" rel="stylesheet"
          type="text/css">
    <link href="{!! url('asset/plugins/bower_components/toast-master/css/jquery.toast.css') !!}" rel="stylesheet">

    <link href="{!! url('/asset/plugins/bower_components/icheck/skins/all.css') !!}" rel="stylesheet">
    <link href="{!! url('/asset/plugins/bower_components/custom-select/custom-select.css') !!}" rel="stylesheet"
          type="text/css"/>
    <link href="{!! url('/asset/css/core-style.css') !!}" rel="stylesheet" type="text/css"/>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    @yield('css')
</head>
<body>
<!-- Preloader -->
<div class="preloader">
    <div class="cssload-speeding-wheel"></div>
</div>
<!-- Top Navigation -->
<nav class="navbar navbar-default navbar-static-top m-b-0">
    <div class="navbar-header">
        <a class="navbar-toggle hidden-sm hidden-md hidden-lg " href="javascript:void(0)"
           data-toggle="collapse" data-target=".navbar-collapse"><i class="ti-menu"></i></a>
        <div class="top-left-part">

        </div>
        <ul class="nav navbar-top-links navbar-left hidden-xs">
            <li><a href="javascript:void(0)" class="open-close hidden-xs waves-effect waves-light"><i
                            class="icon-arrow-left-circle ti-menu"></i></a></li>
        </ul>
        <ul class="nav navbar-top-links navbar-right pull-right">
            <!-- /.dropdown -->
            @if(Auth::user()->can('liveSupport') && Request::is('/'))
                <li class="dropdown user-status-menu">
                    <a class="dropdown-toggle" href="#" data-toggle="dropdown" data-id="0">
                        Durum :
                        <span class="status-icon-container">
                            @switch(Auth::user()->onlinestatus)
                                @case(1)
                                <i class="fa fa-check-circle user-status-icon s1"></i>
                                @break
                                @case(2)
                                <i class="fa fa-clock-o user-status-icon s2"></i>
                                @break
                                @case(3)
                                <i class="fa fa-minus-circle user-status-icon s3"></i>
                                @break
                            @endswitch
                        </span>
                    </a>
                    <ul class="dropdown-menu dropdown-user animated flipInY">
                        <li class="online-row" style="{{ ((Auth::user()->onlinestatus == 1) ? 'display:none' : '') }}"
                            data-id="1">
                            <a href="javascript:;" class="text-center">Çevrimiçi <i
                                        class="fa fa-check-circle"></i></a></li>
                        </li>
                        <li class="busy-row" style="{{ ((Auth::user()->onlinestatus == 2) ? 'display:none' : '') }}"
                            data-id="2">
                            <a href="javascript:;" class="text-center">Hemen Dönecek <i
                                        class="fa fa-clock-o"></i></a></li>
                        </li>
                        <li class="out-row" style="{{ ((Auth::user()->onlinestatus == 3) ? 'display:none' : '') }}"
                            data-id="3">
                            <a href="javascript:;" class="text-center">Meşgul <i
                                        class="fa fa-minus-circle"></i></a>
                        </li>
                    </ul>
                </li>
            @endif
            <li class="dropdown">
                <a class="dropdown-toggle profile-pic user-detail-bar" data-toggle="dropdown" href="#">
                    <b class="hidden-xs" data-id="{!! Auth::user()->id !!}"
                       data-online="{!! Auth::user()->onlinestatus !!}">{!! Auth::user()->name !!}</b>
                </a>
                <ul class="dropdown-menu dropdown-user animated flipInY">
                    <li><a href="{!! url('profile') !!}"><i class="ti-user"></i> Hesap Ayarlarım</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();"><i class="fa fa-power-off"></i> Çıkış
                            Yap</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>
                <!-- /.dropdown-user -->
            </li>

            <!-- /.dropdown -->
        </ul>
    </div>
    <!-- /.navbar-header -->
    <!-- /.navbar-top-links -->
    <!-- /.navbar-static-side -->
</nav>
<!-- End Top Navigation -->
<!-- Left navbar-header -->
<div class="navbar-default sidebar" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            @if(Auth::user()->can('liveSupport'))
                <li>
                    <a href="{!! url('/') !!}" class="waves-effect {!! Request::is('/') ? ' active' : '' !!}">
                    <span class="hide-menu">
                        <i class="fa fa-bullseye"></i>
                        Canlı Destek
                    </span>
                    </a>
                </li>
            @endif

            @if(Auth::user()->can('role') || Auth::user()->can('user') || Auth::user()->can('shift'))
                <li>
                    <a href="javascript:;"
                       class="waves-effect {!! (Request::is('roles*') || Request::is('users*')) ? ' active' : '' !!}">
                    <span class="hide-menu">
                        <i class="fa fa-users"></i> Kullanıcı Yönetimi
                    </span>
                    </a>
                    <ul class="nav nav-second-level collapse">
                        @if(Auth::user()->can('role'))
                            <li>
                                <a {!! (Request::is('roles*')) ? ' class="active"' : '' !!} href="{!! url('roles') !!}"><i
                                            class="fa fa-user-md"></i> Roller</a>
                            </li>
                        @endif
                        @if(Auth::user()->can('user'))
                            <li><a {!! (Request::is('users*')) ? ' class="active"' : '' !!} href="{!! url('users') !!}"><i
                                            class="fa fa-user"></i> Kullanıcılar</a>
                            </li>
                        @endif
                        @if(Auth::user()->can('shift'))
                            <li>
                                <a {!! (Request::is('shifts*')) ? ' class="active"' : '' !!} href="{!! url('shifts') !!}"><i
                                            class="ti-notepad"></i> Mesailer</a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if(Auth::user()->can('preparedContent') || Auth::user()->can('welcomeMessage') || Auth::user()->can('bannedWord'))
                <li>
                    <a href="javascript:;"
                       class="waves-effect{!! (Request::is('preparedContents*') || Request::is('welcomeMessages*') || Request::is('bannedWords*')) ? ' active' : '' !!}"
                       href="{!! url('preparedContents') !!}">
                    <span class="hide-menu">
                        <i class="fa fa-comments"></i> İçerik Yönetimi
                    </span>
                    </a>
                    <ul class="nav nav-second-level collapse">
                        @if(Auth::user()->can('preparedContent'))
                            <li>
                                <a {!! (Request::is('preparedContents*')) ? ' class="active"' : '' !!} href="{!! url('preparedContents') !!}">
                                    <i class="ti-control-shuffle"></i> Hazır İçerikler
                                </a>
                            </li>
                        @endif
                        @if(Auth::user()->can('welcomeMessage'))
                            <li>
                                <a {!! (Request::is('welcomeMessages*')) ? ' class="active"' : '' !!} href="{!! url('welcomeMessages') !!}">
                                    <i class="fa fa-send"></i> Karşılama Mesajları
                                </a>
                            </li>
                        @endif
                        @if(Auth::user()->can('bannedWord'))
                            <li>
                                <a {!! (Request::is('bannedWords*')) ? ' class="active"' : '' !!} href="{!! url('bannedWords') !!}">
                                    <i class="fa fa-ban"></i> Yasaklı Kelimeler
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif
            @if(Auth::user()->can('report'))
                <li>
                    <a href="{!! url('reports') !!}"
                       class="waves-effect{!! ((Request::is('reports*')) ? ' active' : '') !!}">
                        <i class="fa fa-flag-checkered"></i>
                        Raporlar
                    </a>
                </li>
            @endif

            @if(Auth::user()->can('config') || Auth::user()->can('bannedUser') || Auth::user()->can('subject'))
                <li>
                    <a href="javascript:;"
                       class="wawes-effect{!! ((Request::is('configs*') || Request::is('bannedUsers*') || Request::is('subjects*')) ? ' active' : '') !!}">
                        <i class="fa fa-forward"></i>
                        Diğer İşlemler
                    </a>
                    <ul class="nav nav-second-level collapse">
                        @if(Auth::user()->can('subject'))
                            <li>
                                <a href="{!! url('subjects') !!}"
                                   class="waves-effect{!! ((Request::is('subjects*')) ? ' active' : '') !!}">
                                    <i class="fa fa-list"></i>
                                    Görüşme Konuları
                                </a>
                            </li>
                        @endif
                        @if(Auth::user()->can('bannedUser'))
                            <li>
                                <a href="{!! url('bannedUsers') !!}"
                                   class="waves-effect{!! ((Request::is('bannedUsers*')) ? ' active' : '') !!}">
                                    <i class="fa fa-ban"></i>
                                    Engellenen Ziyaretçiler
                                </a>
                            </li>
                        @endif
                        @if(Auth::user()->can('config'))
                            <li>
                                <a href="{!! url('configs') !!}"
                                   class="waves-effect{!! ((Request::is('configs*')) ? ' active' : '') !!}">
                                    <i class="fa fa-cogs"></i>
                                    Ayarlar
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

        </ul>
    </div>
</div>
<!-- Left navbar-header end -->
<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        @if(!isset($breadcrumb) || $breadcrumb)
            <div class="row bg-title">
                <div class="col-lg-5 col-md-6 col-sm-6 col-xs-12">
                    <h4 class="page-title">@yield('title')</h4>
                </div>
                <div class="col-lg-7 col-sm-6 col-md-6 col-xs-12">
                    <ol class="breadcrumb">
                        <li><a href="/">Ana Sayfa</a></li>
                        @yield('breadcrumb')
                    </ol>
                </div>
            </div>
            <!-- /.col-lg-12 -->
        @else
            @yield('breadcrumb')
        @endif

    <!-- BEGIN MESSAGES -->
        @if(Session::has('alert'))
            <div class="alert alert-{{ Session::get('alert.type') }} alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
                <b>
                    <?php
                    $alertMessage = ['success' => 'Başarılı!', 'danger' => 'Başarısız!', 'warning' => 'Uyarı!'];
                    ?>
                    @if(!empty(Session::get('alert.head')))
                        {{ Session::get('alert.head') }}
                    @else
                        {{ $alertMessage[Session::get('alert.type')] }}
                    @endif
                </b>
                {{ Session::get('alert.message') }}
            </div>
        @endif

        @yield('content')
    </div>
</div>
@yield('footer')

<!-- jQuery -->
<script src="{!! url('asset/plugins/bower_components/jquery/dist/jquery.min.js') !!}"></script>
<!-- Bootstrap Core JavaScript -->
<script src="{!! url('asset/bootstrap/dist/js/tether.min.js') !!}"></script>
<script src="{!! url('asset/bootstrap/dist/js/bootstrap.min.js') !!}"></script>
<script src="{!! url('asset/plugins/bower_components/bootstrap-extension/js/bootstrap-extension.min.js') !!}"></script>
<!-- Menu Plugin JavaScript -->
<script src="{!! url('asset/plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js') !!}"></script>
<!--slimscroll JavaScript -->
<script src="{!! url('asset/js/jquery.slimscroll.js') !!}"></script>
<!--Wave Effects -->
<script src="{!! url('asset/js/waves.js') !!}"></script>
<!-- Custom Theme JavaScript -->
<script src="{!! url('asset/js/custom.min.js') !!}"></script>

<script type="text/javascript" src="{!! url('/asset/plugins/bower_components/blockUI/jquery.blockUI.js') !!}"></script>
<script src="{!! url('asset/plugins/datatable/js/app-menu.min.js') !!}" type="text/javascript"></script>
<script src="{!! url('asset/plugins/datatable/js/app.min.js') !!}" type="text/javascript"></script>
<script src="{!! url('asset/js/Loader.js') !!}"></script>
<script src="{!! url('asset/js/validator.js') !!}" type="text/javascript"></script>

<script src="{!! url('asset/plugins/bower_components/sweetalert/sweetalert.min.js') !!}"></script>
<script src="{!! url('asset/plugins/bower_components/toast-master/js/jquery.toast.js') !!}"></script>
<script src="{!! url('asset/plugins/bower_components/icheck/icheck.min.js') !!}"></script>
<script src="{!! url('asset/plugins/bower_components/icheck/icheck.init.js') !!}"></script>
<!-- Core Js -->
<script src="{!! url('asset/core/js/helpers.js') !!}"></script>
<script src="{!! url('asset/core/js/core.js') !!}"></script>

@yield('js')
</body>
</html>