@extends('layouts.app')
@section('title') Ayarlar @endsection
@section('breadcrumb')
    <li class="active">Ayarlar</li>
@endsection
@section('js')
    <script src="{!! url('/asset/plugins/bower_components/ckeditor/ckeditor.js') !!}"></script>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <form action="{!! url('configs/update/') !!}" method="POST"
                      class="form-material form-horizontal" data-toggle="validator" novalidate="true">
                    {{ csrf_field() }}
                    @if(count($configs) > 0)
                        @foreach($configs as $config)
                            @if(isset($formFields[$config->name]))
                                @switch($formFields[$config->name]['form'])
                                @case('radio')
                                <div class="form-group">
                                    <label class="col-md-12">{!! $formFields[$config->name]['text'] !!}</label>
                                    <div class="col-md-12">
                                        @foreach($formFields[$config->name]['values'] as $key => $text)
                                            <input type="radio" class="check" id="flat-radio-1{!! $config->id . $key !!}" name="{!! $config->name !!}" value="{!! $key !!}"
                                                   data-radio="iradio_flat-red"{!! (($config->value == $key) ? ' checked' : '') !!}>
                                            <label for="flat-radio-1{!! $config->id . $key !!}">{!! $text !!}</label>
                                        @endforeach
                                    </div>
                                </div>
                                @break
                                @case('text')
                                <div class="form-group">
                                    <label class="col-md-12">{!! $formFields[$config->name]['text'] !!}</label>
                                    <div class="col-md-12">
                                        <input name="{!! $config->name !!}" class="form-control" type="text" value="{!! $config->value !!}">
                                    </div>
                                </div>
                                @break
                                @endswitch
                            @endif
                        @endforeach
                    @endif
                    <button type="submit" class="btn btn-success waves-effect waves-light"><i class="fa fa-check"></i>
                        Kaydet
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection