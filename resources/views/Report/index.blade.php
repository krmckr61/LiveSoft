@extends('layouts.app')
@section('title') Raporlar @endsection
@section('breadcrumb')
    <li class="active">Raporlar</li>
@endsection
@section('css')
    <link href="{!! url('asset/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') !!}"
          rel="stylesheet" type="text/css"/>
    <link href="{!! url('asset/plugins/bower_components/bootstrap-daterangepicker/daterangepicker.css') !!}"
          rel="stylesheet">
@endsection
@section('js')
    <script src="{!! url('asset/plugins/bower_components/moment/moment.js') !!}"></script>
    <script src="{!! url('asset/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') !!}"></script>
    <script src="{!! url('asset/plugins/bower_components/bootstrap-daterangepicker/daterangepicker.js') !!}"></script>
    <script src="/asset/pages/report/report.js" type="text/javascript"></script>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-3">
            <div class="panel panel-default">
                <button class="btn-lg btn-success btn-block" id="GetReport">
                    <i class="fa fa-flag-checkered"></i>
                    Raporu Görüntüle
                </button>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" data-perform="panel-collapse">
                    Tarih Aralığı Seçimi
                    <div class="panel-action">
                        <a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
                    </div>
                </div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <input type="text" class="form-control" id="DateRange">
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading" data-perform="panel-collapse">
                    Kullanıcı Seçimi
                    <div class="panel-action"><a href="#" data-perform="panel-collapse"><i class="ti-minus"></i></a>
                    </div>
                </div>
                <div class="panel-wrapper collapse in" aria-expanded="true">
                    <div class="panel-body">
                        <ul class="icheck-list p-0">
                            @if(count($users) > 0)
                                <li>
                                    <input type="checkbox" class="check check-all-users" id="square-checkbox-0"
                                           data-checkbox="icheckbox_square-blue">
                                    <label for="square-checkbox-0">Tümünü Seç/Kaldır</label>
                                </li>
                                <li>
                                    <hr class="m-0">
                                </li>
                                @foreach($users as $user)
                                    <li>
                                        <input type="checkbox" class="check user-checkbox"
                                               id="square-checkbox-{!! $user->id !!}"
                                               data-checkbox="icheckbox_square-blue" value="{!! $user->id !!}">
                                        <label for="square-checkbox-{!! $user->id !!}">{!! $user->name !!}</label>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9 report-detail">
            <div class="row">
                <div class="col-sm-12">
                    <div class="white-box text-right">
                        <button class="btn btn-success" id="Download"><i class="fa fa-file-excel-o"></i> İndir (.xls)</button>
                    </div>
                    <div class="white-box">
                        <table class="table table-stripped table-hover" id="ReportTable">
                            <thead>
                            <tr>
                                <th colspan="2"><h3 class="box-title">Rapor Detayları</h3></th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        <table class="hidden detail-hidden">
                            <tr class="clone">
                                <td class="name"></td>
                                <td class="value"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="white-box">
                        <table class="hidden blocked-hidden">
                            <tr class="clone">
                                <td class="name"></td>
                                <td class="value"></td>
                                <td class="value2"></td>
                            </tr>
                        </table>
                        <table class="table table-stripped table-hover" id="BlockedWords">
                            <thead>
                            <tr>
                                <th colspan="3">
                                    <h3 class="box-title">Yasaklı Kelimeler</h3>
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    Temsilci
                                </th>
                                <th>
                                    Kelime
                                </th>
                                <th>
                                    Tarih
                                </th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="white-box">
                        <table class="hidden lowscore-hidden">
                            <tr class="clone">
                                <td class="username"></td>
                                <td class="point"></td>
                                <td class="visitid"></td>
                            </tr>
                        </table>
                        <table class="table table-stripped table-hover" id="LowScoreVisits">
                            <thead>
                            <tr>
                                <th colspan="3">
                                    <h3 class="box-title">Düşük Puanlı Görüşmeler</h3>
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    Temsilci
                                </th>
                                <th>
                                    Puan
                                </th>
                                <th>
                                    Görüşme
                                </th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9 no-report">
            <div class="white-box">
                <div class="alert alert-warning">
                    Lütfen görüntülemek istediğiniz raporu filtreleyiniz.
                </div>
            </div>
        </div>
    </div>
@endsection