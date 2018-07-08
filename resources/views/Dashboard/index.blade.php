@extends('layouts.app')
@section('title') Canlı Destek @endsection
@section('css')
    <link href="{!! url('asset/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') !!}"
          rel="stylesheet" type="text/css"/>
    <link href="{!! url('asset/plugins/bower_components/bootstrap-daterangepicker/daterangepicker.css') !!}"
          rel="stylesheet">
    <link href="{!! url('asset/plugins/bower_components/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') !!}"
          rel="stylesheet">
    <link href="{!! url('asset/plugins/bower_components/datatables/jquery.dataTables.min.css') !!}" rel="stylesheet"
          type="text/css"/>
    <link rel="stylesheet" href="{!! url('asset/plugins/bower_components/html5-editor/bootstrap-wysihtml5.css') !!}"/>
@endsection
@section('js')
    <script src="{!! url('asset/plugins/bower_components/moment/moment.js') !!}"></script>
    <script src="{!! url('asset/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') !!}"></script>
    <script src="{!! url('asset/plugins/bower_components/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') !!}"></script>
    <script src="{!! url('asset/plugins/bower_components/bootstrap-daterangepicker/daterangepicker.js') !!}"></script>
    <script src="{!! url('asset/plugins/bower_components/bootstrap-treeview-master/dist/bootstrap-treeview.min.js') !!}"></script>
    <script src="{!! url('asset/plugins/bower_components/html5-editor/wysihtml5-0.3.0.js') !!}"></script>
    <script src="{!! url('asset/plugins/bower_components/html5-editor/bootstrap-wysihtml5.js') !!}"></script>
    <script src="{!! url('asset/plugins/kTab/kTab.js') !!}"></script>
    <script src="{!! url('asset/plugins/bower_components/jquery.hotkeys/jquery.hotkeys.js') !!}"></script>
    <script src="{!! url('asset/plugins/bower_components/datatables/jquery.dataTables.min.js') !!}"></script>
    <script src="{!! url('asset/plugins/bower_components/jquery.pulsate/jquery.pulsate.min.js') !!}"></script>
    <script type="text/javascript" src="{!! url('asset/js/cbpFWTabs.js') !!}"></script>
    <script src="{!! config('app.socketUrl') . 'socket.io/socket.io.js' !!}"></script>
    <script src="{!! url('asset/node/table.js?v=1.3') !!}"></script>
    <script src="{!! url('asset/node/index.js?v=1.3') !!}"></script>
    <script src="{!! url('asset/pages/home/chat.js?v=1.3') !!}"></script>
    <script type="text/javascript" src="{!! url('asset/pages/home/index.js?v=1.3') !!}"></script>
@endsection
@section('content')
    <audio id="Audio" src="/sounds/client2.mp3"></audio>
    <audio id="NewMessageAudio" src="/sounds/new-message.mp3"></audio>
    <audio id="NotificationAudio" src="/sounds/notification.mp3"></audio>
    <section id="LiveSection">
        <div class="sttabs tabs-style-line">
            <nav>
                <ul>
                    <li><a href="#Visitors"><span><i class="fa fa-bullseye"></i> Ziyaretçiler</span></a></li>
                    <li><a href="#History"><span><i class="fa fa-clock-o"></i> Ziyaret Geçmişi</span></a></li>
                    <li><a href="#Representatives"><span><i class="fa fa-users"></i> Temsilciler</span></a></li>
                </ul>
            </nav>
            <div class="content-wrap">
                <section id="Visitors">
                    {{--<h3><i class="fa fa-bullseye"></i> Ziyaretçiler</h3>--}}
                    <div id="VisitorTab" class="col-sm-12">
                        <div class="row heads">
                            <div class="col-sm-6 bar active" data-target="#LiveClient" id="LiveBar">
                                <h3><i class="fa fa-check"></i> Canlı Desteğe Bağlananlar (<span>0</span>)</h3>
                            </div>
                            <div class="col-sm-6 bar" data-target="#VisitorClient" id="VisitorBar">
                                <h3><i class="fa fa-bullseye"></i> Site Ziyaretçileri (<span>0</span>)</h3>
                            </div>
                        </div>
                        <div class="row contents">
                            <div class="content active col-sm-12" id="LiveClient">
                                <div class="table-responsive">
                                    <table id="LoginVisitorTable" class="table table-striped live-datatable">
                                        <thead>
                                        <tr>
                                            <th width="5%">***</th>
                                            <th width="5%">Durum</th>
                                            <th>Kullanıcı Adı</th>
                                            <th>Adı Soyadı</th>
                                            <th>E-Posta</th>
                                            <th width="5%">Ülke</th>
                                            <th width="10%">Zaman</th>
                                            <th>Temsilci</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="content  col-sm-12" id="VisitorClient">
                                <div class="table-responsive">
                                    <table id="VisitorTable" class="table table-striped live-datatable">
                                        <thead>
                                        <tr>
                                            <th><i class="fa fa-comments-o"></i></th>
                                            <th>Ülke</th>
                                            <th>Şehir</th>
                                            <th>Ziyaret Başlangıcı</th>
                                            <th>Cihaz</th>
                                            <th><i class="fa fa-globe"></i></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="History">
                    <div class="table-responsive">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="dpicker">Tarih Aralığı Seçimi</label>
                                        <input type="text" id="HistoryDateRangePicker"
                                               class="form-control datepicker-with-time">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                </div>
                                <div class="col-sm-4 text-right">
                                    <button id="HistorySearchButton" class="btn btn-lg btn-success"><i
                                                class="fa fa-search"></i> Listele
                                    </button>
                                </div>
                            </div>
                        </div>
                        <table id="HistoryTable" class="table table-striped live-datatable">
                            <thead>
                            <tr>
                                <th width="5%">Kullanıcı Adı</th>
                                <th width="10%">Adı Soyadı</th>
                                <th width="5%">E-Posta</th>
                                <th width="10%">Ülke</th>
                                <th width="10%">Chat Başlangıç</th>
                                <th width="10%">Chat Bitiş</th>
                                <th width="10%">Chat Süresi</th>
                                <th width="10%">Temsilci</th>
                                <th width="10%">İncele</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </section>
                <section id="Representatives">
                    <div class="table-responsive">
                        <table id="UserTable" class="table table-striped live-datatable">
                            <thead>
                            <tr>
                                <th width="5%">Durum</th>
                                <th width="5%">Süre</th>
                                <th width="5%">Temsilci No</th>
                                <th width="100%">Temsilci Adı</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            <!-- /content -->
            <!-- /tabs -->
        </div>
    </section>
    <div class="chat-screen-container"></div>
@endsection
@section('footer')
    <footer class="footer">
        <div class="row">
            <div class="col-1">
                <button class="btn btn-default btn-block btn-take-client" id="WaitingCount" title="Bekleyen Ziyaretçi Sayısı" disabled>
                    <i class="fa fa-user-plus"></i> (<span>0</span>)
                </button>
            </div>
            <div class="col-11 relative">
                <div class="min-message-direction direction-left">
                    <i class="fa fa-arrow-circle-left"></i>
                </div>
                <div class="min-message-container"></div>
                <div class="min-message-direction direction-right">
                    <i class="fa fa-arrow-circle-right"></i>
                </div>
            </div>
        </div>
    </footer>

    <div class="clone">
        <textarea id="PreparedContents" class="hidden">{!! $preparedContents !!}</textarea>
    </div>

    <div class="modal fade" id="UserListModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <table class="dataTable">
                        <thead>
                        <tr>
                            <th>Durum</th>
                            <th>Süre</th>
                            <th>Temsilci Adı</th>
                            <th>*</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal"><i class="fa fa-check"></i> Tamam
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    @if(Auth::user()->can('privateMessage'))
        <div class="modal fade" id="PrivateMessageModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form class="form-material form-horizontal" id="PrivateMessageForm" action="javascript:;"
                          data-toggle="validator" novalidate="true">

                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Özel Mesaj Gönder</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="privateMessageText">Mesaj</label>
                                <input type="text" class="form-control" id="privateMessageText"
                                       placeholder="Mesajınızı Giriniz"
                                       required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal"><i
                                        class="fa fa-times"></i> Vazgeç
                            </button>
                            <button type="submit" class="btn btn-success"><i class="fa fa-send"></i> Gönder
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <div class="modal fade" id="UserBanModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form class="form-material form-horizontal" id="UserBanForm" action="javascript:;"
                      data-toggle="validator" novalidate="true">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Ziyaretçi Engelle</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Ziyaretçi Bilgileri</label>
                            <div class="row ban-user-details">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Tarih Seçimi</label>
                            <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Tarih Seçimi"
                                   required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal"><i
                                    class="fa fa-times"></i> Vazgeç
                        </button>
                        <button type="submit" class="btn btn-danger"><i class="fa fa-ban"></i> Engelle
                        </button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div id="Clones">
        <div class="table-child-data clone container">
            <div class="row">
                <div class="col-sm-3 text-right">Ülke :</div>
                <div class="col-sm-9 text-left country"></div>
                <div class="col-sm-3 text-right">Şehir :</div>
                <div class="col-sm-9 text-left city"></div>
                <div class="col-sm-3 text-right">Tarayıcı :</div>
                <div class="col-sm-9 text-left browser"></div>
                <div class="col-sm-3 text-right">İşletim Sistemi :</div>
                <div class="col-sm-9 text-left os"></div>
                <div class="col-sm-3 text-right">Ziyaret Başlangıcı :</div>
                <div class="col-sm-9 text-left connectionTime"></div>
                <div class="col-sm-3 text-right">Giriş Türü :</div>
                <div class="col-sm-9 text-left loginType"></div>
                @if(Auth::user()->can('watchChat'))
                    <div class="col-sm-3 text-right ban-row">Görüşmeyi İzle :</div>
                    <div class="col-sm-9 text-left ban-row">
                        <button type="button" class="btn btn-xs btn-default watch-chat-button" title="Görüşmeyi İzle">
                            <i class="fa fa-eye"></i> Görüşmeyi İzle
                        </button>
                    </div>
                @endif
                @if(Auth::user()->can('joinChat'))
                    <div class="col-sm-3 text-right ban-row">Görüşmeye Katıl :</div>
                    <div class="col-sm-9 text-left ban-row">
                        <button type="button" class="btn btn-xs btn-info join-chat" title="Görüşmeye Katıl">
                            <i class="fa fa-user-plus"></i> Görüşmeye Katıl
                        </button>
                    </div>
                @endif
                @if(Auth::user()->can('privateMessage'))
                    <div class="col-sm-3 text-right ban-row">Özel Mesaj Gönder :</div>
                    <div class="col-sm-9 text-left ban-row">
                        <button type="button" class="btn btn-xs btn-success private-message" title="Özel Mesaj Gönder">
                            <i class="fa fa-comment"></i> Özel Mesaj
                        </button>
                    </div>
                @endif
                @if(Auth::user()->can('banUser'))
                    <div class="col-sm-3 text-right ban-row">Bu kullanıcıyı engelle :</div>
                    <div class="col-sm-9 text-left ban-row">
                        <button type="button" class="btn btn-xs btn-danger user-ban" title="Bu ziyaretçiyi engelle"><i
                                    class="fa fa-ban"></i> Engelle
                        </button>
                    </div>
                @endif
            </div>

        </div>


        <!-- minimize chat -->
        <div class="min-message clone">
            <div class="unread-message-count hidden"></div>
            <div class="text"></div>
        </div>
        <!-- minimize chat /end -->

        <!-- message -->
        <div class="message-container clone">
            <div class="detail sender-name"></div>
            <div class="detail send-time"></div>
            <div class="text"></div>
        </div>
        <!-- message /end -->

        <!-- client info row -->
        <div class="client-detail-row col-sm-12 clone">
            <div class="name"></div>
            <div class="value"></div>
        </div>
        <!-- client info row /end -->

        <!-- recent visit -->
        <div class="card clone">
            <div class="card-header" id="" data-toggle="collapse"
                 data-target="#collapseOne" aria-expanded="true"
                 aria-controls="collapseOne">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="chat-date"></div>
                    </div>
                    <div class="col-sm-12">
                        <div class="chat-user"><i class="fa fa-user"></i>
                            <span></span></div>
                    </div>
                </div>
            </div>

            <div id="collapseOne" class="collapse"
                 aria-labelledby="" data-parent="#accordion">
                <div class="card-body">
                    <div class="row recent-message-buttons">
                        <div class="col-sm-6 text-center show-recent-messages active">
                            Mesajlar
                        </div>
                        <div class="col-sm-6 text-center show-recent-infos">
                            Bilgiler
                        </div>
                    </div>
                    <div class="recent-messages">

                    </div>
                    <div class="recent-infos" style="display:none">

                    </div>
                </div>
            </div>

        </div>
        <!-- recent visit /end -->

        <!-- chat screen -->
        <div class="chat-screen current-chat right-sidebar clone">
            <div class="slimscrollright">
                <div class="rpanel-title">
                    <div class="client-name pull-left"></div>
                    <span class="close-chat" title="Görüşmeyi Sonlandır">
                        <i class="fa fa-close"></i>
                    </span>
                    <span class="hide-chat" title="Minimize Et">
                        <i class="fa fa-minus"></i>
                    </span>
                    <div class="clearfix"></div>
                </div>
                <div class="r-panel-body">
                    <div class="row">
                        <div class="col-md-4 grid chat-container">
                            <div class="shortcuts-container" style="display: none;">
                                <button class="btn btn-danger btn-block close-shortcuts">Kapat <i
                                            class="fa fa-times-circle"></i></button>
                                <ul>
                                </ul>
                            </div>
                            <div class="transaction-bar">
                                <ul>
                                    <li><i class="fa fa-comments"></i> Mesajlaşma</li>
                                    <li class="close-chat pull-right"><i class="fa fa-power-off"></i>
                                    </li>
                                    <li class="pull-right chat-transaction">
                                        <i class="fa fa-ellipsis-v" title="İşlemler"></i>
                                        <ul>
                                            <li class="private-message" data-id=""><i class="fa fa-comment"></i> Özel
                                                Mesaj Gönder
                                            </li>
                                            <li class="add-user"><i class="fa fa-user-plus"></i> Görüşmeye Temsilci Ekle
                                            </li>
                                            <li class="logout-user"><i class="fa fa-user-times"></i> Görüşmeden Ayrıl
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                            <div class="messages">
                            </div>
                            <div class="send-bar">
                                <div class="text-container">
                                    <textarea class="text-editor message-text"></textarea>
                                    <button class="btn btn-success message-send" id="MessageSend"><i
                                                class="fa fa-send"></i> GÖNDER
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 other-grids">
                            <div class="row">
                                <div class="col-md-4 client-info-container">
                                    <div class="col-sm-12 grid grid2 client-infos">
                                        <div class="transaction-bar">
                                            <ul>
                                                <li><i class="fa fa-info-circle"></i> Ziyaretçi Bilgileri</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 prepared-messages-container">
                                    <div class="col-sm-12 grid grid2">
                                        <div class="transaction-bar">
                                            <ul>
                                                <li><i class="fa fa-folder-open"></i> Hazır İçerikler</li>
                                            </ul>
                                        </div>
                                        <div class="form-group prepared-messages-search-container">
                                            <div class="input-group">
                                                <input type="text" class="form-control search"
                                                       placeholder="Arama">
                                                <div class="input-group-addon search">
                                                    <i class="fa fa-search"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="prepared-messages"></div>
                                    </div>
                                </div>
                                <div class="col-md-4 chat-history-container">
                                    <div class="col-sm-12 grid grid2">
                                        <div class="transaction-bar">
                                            <ul>
                                                <li><i class="fa fa-clock-o"></i> Konuşma Geçmişi</li>
                                            </ul>
                                        </div>
                                        <div class="history-messages-container">
                                            <div id="accordion" class="col-sm-12">
                                                <div class="row">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- chat screen /end -->

        <!-- history chat screen -->
        <div class="chat-screen history-chat right-sidebar clone">
            <div class="slimscrollright">
                <div class="rpanel-title">
                    <div class="client-name pull-left"></div>
                    <span class="close-chat" title="Görüşmeyi Sonlandır">
                        <i class="fa fa-close"></i>
                    </span>
                    <span class="hide-chat" title="Minimize Et">
                        <i class="fa fa-minus"></i>
                    </span>
                    <div class="clearfix"></div>
                </div>
                <div class="r-panel-body">
                    <div class="row">
                        <div class="col-sm-12 heads">
                            <div class="row">
                                <div class="col-sm-4 bar-choosing active default-active" data-target=".chat-container">
                                    <i class="fa fa-comments"></i> Mesajlaşma
                                </div>
                                <div class="col-sm-4 bar-choosing" data-target=".client-info-container"><i
                                            class="fa fa-info-circle"></i> Ziyaretçi Bilgileri
                                </div>
                                <div class="col-sm-4 bar-choosing" data-target=".chat-history-container"><i
                                            class="fa fa-clock-o"></i> Konuşma Geçmişi
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 grid chat-container bar-choosing-container">
                            <div class="shortcuts-container" style="display: none;">
                                <button class="btn btn-danger btn-block close-shortcuts">Kapat <i
                                            class="fa fa-times-circle"></i></button>
                                <ul>
                                </ul>
                            </div>
                            <div class="messages">
                            </div>
                        </div>

                        <div class="col-md-12 client-info-container bar-choosing-container">
                            <div class="col-sm-12 grid grid2 client-infos">
                            </div>
                        </div>

                        <div class="col-md-12 chat-history-container bar-choosing-container">
                            <div class="col-sm-12 grid grid2">
                                <div class="history-messages-container">
                                    <div id="accordion" class="col-sm-12">
                                        <div class="row">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- history chat screen /end -->


        <!-- watch chat screen -->
        <div class="chat-screen watch-chat right-sidebar clone">
            <div class="slimscrollright">
                <div class="rpanel-title">
                    <div class="client-name pull-left"></div>
                    <span class="close-chat" title="Görüşmeyi Sonlandır">
                        <i class="fa fa-close"></i>
                    </span>
                    <span class="hide-chat" title="Minimize Et">
                        <i class="fa fa-minus"></i>
                    </span>
                    <div class="clearfix"></div>
                </div>
                <div class="r-panel-body">
                    <div class="row">
                        <div class="col-sm-12 heads">
                            <div class="row">
                                <div class="col-sm-4 bar-choosing active default-active" data-target=".chat-container">
                                    <i
                                            class="fa fa-comments"></i> Mesajlaşma
                                </div>
                                <div class="col-sm-4 bar-choosing" data-target=".client-info-container"><i
                                            class="fa fa-info-circle"></i> Ziyaretçi Bilgileri
                                </div>
                                <div class="col-sm-4 bar-choosing" data-target=".chat-history-container"><i
                                            class="fa fa-clock-o"></i> Konuşma Geçmişi
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 grid chat-container bar-choosing-container">
                            <div class="shortcuts-container" style="display: none;">
                                <button class="btn btn-danger btn-block close-shortcuts">Kapat <i
                                            class="fa fa-times-circle"></i></button>
                                <ul>
                                </ul>
                            </div>
                            <div class="messages">
                            </div>
                        </div>

                        <div class="col-md-12 client-info-container bar-choosing-container">
                            <div class="col-sm-12 grid grid2 client-infos">
                            </div>
                        </div>

                        <div class="col-md-12 chat-history-container bar-choosing-container">
                            <div class="col-sm-12 grid grid2">
                                <div class="history-messages-container">
                                    <div id="accordion" class="col-sm-12">
                                        <div class="row">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- watch chat screen /end -->
    </div>
@endsection