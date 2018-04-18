@extends('layouts.app')
@section('title') Karşılama Mesajları @endsection
@section('breadcrumb')
    <li class="active">Karşılama Mesajları</li>
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
                           id="datatable_ajax" data-url="/welcomeMessages/getDatas">
                        <thead>
                        <tr role="row" class="heading">
                            <th width="5%">Mesaj Tipi</th>
                            <th width="5%">Mesaj İçeriği</th>
                            <th width="5%">İşlemler</th>
                        </tr>
                        <tr role="row" class="filter">
                            <td width="35%">
                                <input type="text" class="form-filter"
                                       name="post_type">
                            </td>
                            <td width="35%">
                                <input type="text" class="form-filter"
                                       name="post_content">
                            </td>
                            <td width="30%">
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