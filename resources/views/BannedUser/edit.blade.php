@extends('layouts.app')
@section('title') Engellenen Ziyaretçi İncele @endsection
@section('breadcrumb')
    <li><a href="{!! url('bannedUsers') !!}">Engellenen Ziyaretçiler</a></li>
    <li class="active"><i class="fa fa-info"></i> Düzenle</li>
@endsection
@section('js')
    <script src="{!! url('/asset/plugins/bower_components/ckeditor/ckeditor.js') !!}"></script>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <form action="{!! url('bannedUsers/update/' . $bannedUser->id) !!}" method="POST"
                      class="form-material form-horizontal" data-toggle="validator" novalidate="true">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-md-12">Engelleyen Kullanıcı</label>
                        <div class="col-md-12">
                            {!! $bannedUser->name !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Engellenme Tarihi</label>
                        <div class="col-md-12">
                            {!! $bannedUser->created_at !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Engelin Bitiş Tarihi</label>
                        <div class="col-md-12">
                            {!! $bannedUser->date !!}
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-md-12">Ziyaretçi Bilgileri</label>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-sm-2">Adı Soyadı : </div>
                                <div class="col-sm-10">{!! $clientData->NameSurname ? $clientData->NameSurname : 'N/A' !!}</div>
                            </div>
                            <div class="row">
                                <div class="col-sm-2">Kullanıcı Adı : </div>
                                <div class="col-sm-10">{!! $clientData->UserName ? $clientData->UserName : 'N/A' !!}</div>
                            </div>
                            <div class="row">
                                <div class="col-sm-2">E-Posta Adresi : </div>
                                <div class="col-sm-10">{!! $clientData->Email ? $clientData->Email : 'N/A' !!}</div>
                            </div>
                            <div class="row">
                                <div class="col-sm-2">Bağlantı Türü : </div>
                                <div class="col-sm-10">{!! isset($clientData->FacebookId) ? 'Facebook' : 'Normal' !!}</div>
                            </div>
                        </div>
                    </div>



                    <a href="{!! url('bannedUsers/delete/' . $bannedUser->id) !!}" class="btn btn-success waves-effect waves-light">
                        <i class="fa fa-check"></i>
                        Engeli Kaldır
                    </a>
                </form>
            </div>
        </div>
    </div>
@endsection