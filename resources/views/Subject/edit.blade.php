@extends('layouts.app')
@section('title') Konu Düzenle @endsection
@section('breadcrumb')
    <li><a href="{!! url('users') !!}">Konular</a></li>
    <li class="active"><i class="fa fa-pencil-square-o"></i> Düzenle</li>
@endsection
@section('js')
    <script src="/asset/plugins/bower_components/custom-select/custom-select.min.js" type="text/javascript"></script>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <form action="{!! url('subjects/update/' . $subject->id) !!}" method="POST"
                      class="form-material form-horizontal" data-toggle="validator" novalidate="true">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-md-12">Aktiflik</label>
                        <div class="col-md-12">
                            <input type="radio" class="check" id="flat-radio-1" name="active" value="1"
                                   data-radio="iradio_flat-red"{!! (($subject->active == 1) ? ' checked' : '') !!}>
                            <label for="flat-radio-1">Aktif</label>
                            <input type="radio" class="check" id="flat-radio-2" name="active" value="0"
                                   data-radio="iradio_flat-red"{!! (($subject->active == 0) ? ' checked' : '') !!}>
                            <label for="flat-radio-2">Pasif</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Konu</label>
                        <div class="col-md-12">
                            <input type="text" class="form-control form-control-line" name="name"
                                   value="{!! $subject->name !!}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Açıklama</label>
                        <div class="col-md-12">
                            <textarea name="description" class="form-control" rows="3">{!! $subject->description !!}</textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success waves-effect waves-light">
                        <i class="fa fa-check"></i>
                        Kaydet
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection