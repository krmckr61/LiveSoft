@extends('layouts.app')
@section('title') Yasaklı Kelime @if($bannedWord->status == '1') Düzenle @else Ekle @endif @endsection
@section('breadcrumb')
    <li><a href="{!! url('bannedWords') !!}">Yasaklı Kelimeler</a></li>
    <li class="active"><i class="fa fa-pencil-square-o"></i> Düzenle</li>
@endsection
@section('js')
    <script src="{!! url('/asset/plugins/bower_components/ckeditor/ckeditor.js') !!}"></script>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <form action="{!! url('bannedWords/update/' . $bannedWord->id) !!}" method="POST"
                      class="form-material form-horizontal" data-toggle="validator" novalidate="true">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-md-12">Aktiflik</label>
                        <div class="col-md-12">
                            <input type="radio" class="check" id="flat-radio-1" name="active" value="1"
                                   data-radio="iradio_flat-red"{!! (($bannedWord->active == 1) ? ' checked' : '') !!}>
                            <label for="flat-radio-1">Aktif</label>
                            <input type="radio" class="check" id="flat-radio-2" name="active" value="0"
                                   data-radio="iradio_flat-red"{!! (($bannedWord->active == 0) ? ' checked' : '') !!}>
                            <label for="flat-radio-2">Pasif</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Kelime</label>
                        <div class="col-md-12">
                            <input class="form-control" type="text" name="content" value="{!! $bannedWord->content !!}" placeholder="Kelime" required>
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