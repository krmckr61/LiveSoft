@extends('layouts.app')
@section('title') Engellenen Ziyaretçiler @endsection
@section('breadcrumb')
    <li class="active">Engellenen Ziyaretçiler</li>
@endsection
@section('css')
    <link href="/asset/plugins/datatable/css/datatables.min.css" rel="stylesheet" type="text/css"/>
    <link href="/asset/plugins/datatable/css/datatables.bootstrap.css" rel="stylesheet" type="text/css"/>
@endsection
@section('js')
    <script src="/asset/plugins/datatable/js/datatable.js" type="text/javascript"></script>
    <script src="/asset/plugins/datatable/js/datatables.min.js" type="text/javascript"></script>
    <script src="/asset/plugins/datatable/js/datatables.bootstrap.js" type="text/javascript"></script>
    <script src="/asset/plugins/datatable/js/table-datatables-buttons.min.js" type="text/javascript"></script>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <div class="table-container high">
                    <table class="table table-striped table-bordered table-hover table-has-transactions"
                           id="datatable_ajax" data-url="/bannedUsers/getDatas">
                        <thead>
                        <tr role="row" class="heading">
                            <th width="5%">Durum</th>
                            <th width="5%">Engelleyen Kullanıcı</th>
                            <th width="5%">Engellenme Tarihi</th>
                            <th width="5%">Engelin Bitiş Tarihi</th>
                            <th width="2%">İşlemler</th>
                        </tr>
                        <tr role="row" class="filter">
                            <td width="5%">
                                <select name="post_seen" class="form-filter form-control">
                                    <option value="">Seçiniz</option>
                                    <option value="1">Görüldü</option>
                                    <option value="0">Görülmedi</option>
                                </select>
                            </td>
                            <td width="5%">
                                <select name="post_userid" class="form-filter form-control">
                                    <option value="">Seçiniz</option>
                                    @if(count($users) > 0)
                                        @foreach($users as $user)
                                            <option value="{!! $user->id !!}">{!! $user->name !!}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                            <td width="5%">

                            </td>
                            <td width="5%"></td>
                            <td width="2%">
                                <div class="margin-bottom-5">
                                    <button type="button" class="btn btn-outline btn-info filter-submit">
                                        <i class="fa fa-search"></i> Ara
                                    </button>
                                    <button type="button" class="btn btn-outline btn-danger  filter-cancel">
                                        <i class="fa fa-times"></i> Sıfırla
                                    </button>
                                </div>
                            </td>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection