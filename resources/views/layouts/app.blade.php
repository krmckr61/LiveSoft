<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="{!! config('app.author') !!}">
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    <meta name="siteId" content="{!! config('app.siteCode') !!}">
    <meta name="representationId" content="{!! Auth::user()->id !!}">
    <meta name="socketUrl" content="{!! config('app.socketUrl') !!}">
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
    <link href="{!! url('asset/plugins/bower_components/sweetalert/sweetalert.css') !!}" rel="stylesheet"
          type="text/css">
    <link href="{!! url('asset/plugins/bower_components/toast-master/css/jquery.toast.css') !!}" rel="stylesheet">

    <link href="{!! url('/asset/plugins/bower_components/icheck/skins/all.css') !!}" rel="stylesheet">
    <link href="{!! url('/asset/plugins/bower_components/custom-select/custom-select.css') !!}" rel="stylesheet"
          type="text/css"/>

    <link href="{!! url('/asset/css/core-style.css') !!}" rel="stylesheet" type="text/css"/>

    <link href="" id="theme" rel="stylesheet">
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
        <ul class="nav navbar-top-links navbar-right pull-right">
            <!-- /.dropdown -->

            @if(Auth::user()->can('role') || Auth::user()->can('user') || Auth::user()->can('shift') || Auth::user()->can('preparedContent') || Auth::user()->can('welcomeMessage') || Auth::user()->can('bannedWord') || Auth::user()->can('config') || Auth::user()->can('bannedUser') || Auth::user()->can('subject') || Auth::user()->can('report'))

                <li class="mega-dropdown">
                    <a class="dropdown-toggle waves-effect waves-light" data-toggle="dropdown" href="#"
                       aria-expanded="true"><span>MENU</span><i class="icon-options-vertical"></i></a>
                    <ul class="dropdown-menu mega-dropdown-menu animated bounceInDown">

                        @if(Auth::user()->can('role') || Auth::user()->can('user') || Auth::user()->can('shift'))
                            <li class="col-sm-4">
                                <ul>
                                    <li class="dropdown-header"><i class="fa fa-users"></i> Personel Yönetimi</li>
                                    @if(Auth::user()->can('role'))
                                        <li><a href="{!! url('/roles') !!}"><i class="fa fa-user-md"></i> Roller</a></li>
                                    @endif
                                    @if(Auth::user()->can('user'))
                                        <li><a href="{!! url('/users') !!}"><i class="fa fa-user"></i> Personeller</a></li>
                                    @endif
                                    @if(Auth::user()->can('shift'))
                                        <li><a href="{!! url('/shifts') !!}"><i class="ti-notepad"></i> Mesailer</a></li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                        @if(Auth::user()->can('preparedContent') || Auth::user()->can('welcomeMessage') || Auth::user()->can('bannedWord'))
                            <li class="col-sm-4">
                                <ul>
                                    <li class="dropdown-header"><i class="fa fa-comments"></i> İçerik Yönetimi</li>
                                    @if(Auth::user()->can('preparedContent'))
                                        <li>
                                            <a href="{!! url('/preparedContents') !!}"><i class="ti-control-shuffle"></i> Hazır
                                                İçerikler</a>
                                        </li>
                                    @endif
                                    @if(Auth::user()->can('welcomeMessage'))
                                        <li>
                                            <a href="{!! url('/welcomeMessages') !!}"><i class="fa fa-send"></i> Karşılama Mesajları</a>
                                        </li>
                                    @endif
                                    @if(Auth::user()->can('bannedWord'))
                                        <li>
                                            <a href="{!! url('/bannedWords') !!}"><i class="fa fa-ban"></i> Yasaklı Kelimeler</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif

                        @if(Auth::user()->can('config') || Auth::user()->can('bannedUser') || Auth::user()->can('subject') || Auth::user()->can('report'))
                            <li class="col-sm-4">
                                <ul>
                                    <li class="dropdown-header"><i class="fa fa-forward"></i> Diğer İşlemler</li>
                                    @if(Auth::user()->can('subject'))
                                        <li>
                                            <a href="{!! url('/subjects') !!}"><i class="fa fa-list"></i> Görüşme Konuları</a>
                                        </li>
                                    @endif
                                    @if(Auth::user()->can('bannedUser'))
                                        <li>
                                            <a href="{!! url('/bannedUsers') !!}"><i class="fa fa-ban"></i> Engellenen Ziyaretçiler</a>
                                        </li>
                                    @endif
                                    @if(Auth::user()->can('config'))
                                        <li>
                                            <a href="{!! url('/configs') !!}"><i class="fa fa-cogs"></i> Ayarlar</a>
                                        </li>
                                    @endif
                                    @if(Auth::user()->can('report'))
                                        <li>
                                            <a href="{!! url('/reports') !!}"><i class="fa fa-flag-checkered"></i> Raporlar</a>
                                        </li>
                                    @endif
                                </ul>
                            </li>
                        @endif
                    </ul>
                </li>

            @endif

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
                    <b data-id="{!! Auth::user()->id !!}"
                       data-online="{!! Auth::user()->onlinestatus !!}">{!! Auth::user()->name !!}</b>
                </a>
                <ul class="dropdown-menu dropdown-user dropdown-blank animated flipInY">
                    <li><a href="{!! url('profile') !!}"><i class="ti-user"></i> Hesap Ayarlarım</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a id="LogoutLink" href="{{ route('logout') }}"
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

            <li class="right-side-toggle"> <a class="waves-effect waves-light" href="javascript:void(0)"><i class="ti-palette"></i></a></li>


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
    <div class="sidebar-nav navbar-menu" aria-expanded="false">
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


<!-- .right-sidebar -->
<div class="right-sidebar color-schema">
    <div class="slimscrollright">
        <div class="rpanel-title"> <i class="ti-palette"></i> Renk Seçenekleri <span><i class="ti-close right-side-toggle"></i></span> </div>
        <div class="r-panel-body">
            <ul id="themecolors">
                <li><a href="javascript:void(0)" theme="default" class="default-theme">1</a></li>
                <li><a href="javascript:void(0)" theme="green" class="green-theme">2</a></li>
                <li><a href="javascript:void(0)" theme="gray" class="yellow-theme">3</a></li>
                <li><a href="javascript:void(0)" theme="blue" class="blue-theme">4</a></li>
                <li><a href="javascript:void(0)" theme="purple" class="purple-theme">5</a></li>
                <li><a href="javascript:void(0)" theme="megna" class="megna-theme working">6</a></li>
                <br/>
                <li><a href="javascript:void(0)" theme="default-dark" class="default-dark-theme">7</a></li>
                <li><a href="javascript:void(0)" theme="green-dark" class="green-dark-theme">8</a></li>
                <li><a href="javascript:void(0)" theme="gray-dark" class="yellow-dark-theme">9</a></li>
                <li><a href="javascript:void(0)" theme="blue-dark" class="blue-dark-theme">10</a></li>
                <li><a href="javascript:void(0)" theme="purple-dark" class="purple-dark-theme">11</a></li>
                <li><a href="javascript:void(0)" theme="megna-dark" class="megna-dark-theme">12</a></li>
            </ul>
        </div>
    </div>
</div>
<!-- /.right-sidebar -->

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

<script src="{!! url('asset/plugins/bower_components/styleswitcher/jQuery.style.switcher.js') !!}"></script>

<script src="{!! url('asset/core/js/helpers.js?v=1.1') !!}"></script>
<script src="{!! url('asset/core/js/core.js') !!}"></script>

@yield('js')
</body>
</html>