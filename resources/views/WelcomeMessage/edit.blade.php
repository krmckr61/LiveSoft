@extends('layouts.app')
@section('title') Karşılama Mesajı Düzenle @endsection
@section('breadcrumb')
    <li><a href="{!! url('welcomeMessages') !!}">Karşılama Mesajları</a></li>
    <li class="active"><i class="fa fa-pencil-square-o"></i> Düzenle</li>
@endsection
@section('js')
    <script src="{!! url('/asset/plugins/bower_components/ckeditor/ckeditor.js') !!}"></script>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <form action="{!! url('welcomeMessages/update/' . $message->id) !!}" method="POST"
                      class="form-material form-horizontal" data-toggle="validator" novalidate="true">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-md-12">Mesajın Gönderim Türü</label>
                        <div class="col-md-12">
                            {!! $message->name !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Mesaj İçeriği</label>
                        <div class="col-md-12">
                            <textarea name="content" class="ckeditor">{!! $message->content !!}</textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success waves-effect waves-light"><i class="fa fa-check"></i>
                        Kaydet
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection