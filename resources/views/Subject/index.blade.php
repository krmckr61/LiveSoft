@extends('layouts.app')
@section('title') Konular @endsection
@section('breadcrumb')
    <li class="active">Konular</li>
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
                <a href="{!! url('subjects/add') !!}" class="btn btn-success pull-right m-b-10">
                    <i class="fa fa-plus"></i> Yeni Ekle
                </a>
                <div class="table-container high">
                    <table class="table table-striped table-bordered table-hover table-has-transactions"
                           id="datatable_ajax" data-url="/subjects/getDatas">
                        <thead>
                        <tr role="row" class="heading">
                            <th width="3%">A/P</th>
                            <th width="3%">Konu</th>
                            <th width="3%">Açıklama</th>
                            <th width="5%">İşlemler</th>
                        </tr>
                        <tr role="row" class="filter">
                            <td width="10%">
                                {{ csrf_field() }}
                                <select name="post_active" class="form-filter form-control">
                                    <option value="">Seçiniz</option>
                                    <option value="1">Aktif</option>
                                    <option value="2">Pasif</option>
                                </select>
                            </td>
                            <td width="35%">
                                <input type="text" class="form-filter"
                                       name="post_name">
                            </td>
                            <td width="35%">
                                <input type="text" class="form-filter"
                                       name="post_description">
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