@extends('layouts.app')
@section('title') Görüşmeler @endsection
@section('content')
    <div class="chat-screen right-sidebar old-chat-screen shw-rside">
        <div class="slimscrollright">
            <div class="rpanel-title">
                <div class="client-name pull-left">{!! $data->NameSurname !!}</div>
                <div class="clearfix"></div>
            </div>
            <div class="r-panel-body">
                <div class="row">
                    <div class="col-md-4 grid chat-container">
                        <div class="transaction-bar">
                            <ul>
                                <li><i class="fa fa-comments"></i> Mesajlaşma</li>
                            </ul>
                        </div>
                        <div class="messages">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="col-sm-12 grid grid2 client-infos">
                                    <div class="transaction-bar">
                                        <ul>
                                            <li><i class="fa fa-info-circle"></i> Ziyaretçi Bilgileri</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
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
                            <div class="col-md-4">
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
@endsection