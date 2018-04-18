@extends('layouts.app')
@section('title') {!! $role->display_name !!} Yetkilendirme @endsection
@section('breadcrumb')
    <li><a href="{!! url('roles') !!}">Roller</a></li>
    <li class="active"><i class="fa fa-lock"></i> Yetkilendirme</li>
@endsection
@section('js')
    <script src="/asset/plugins/bower_components/custom-select/custom-select.min.js" type="text/javascript"></script>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <form method="POST" action="{!! url('roles/delegation/update/' . $role->id) !!}"
                      class="form-material form-horizontal">
                    {{ csrf_field() }}
                    @if(count($permissions) > 0)
                        <?php $i = 0 ?>
                        @foreach($permissions as $permission)
                            <div class="form-group">
                                <label class="col-md-12">{!! $permission->display_name !!}</label>
                                <div class="col-md-12">
                                    <input type="radio" class="check" id="flat-radio-{!! $i !!}"
                                           name="auth[{!! $permission->name !!}]"
                                           value="1"
                                           data-radio="iradio_flat-red" @if($permission->auth == 1) checked @endif>
                                    <label for="flat-radio-{!! $i !!}">Aktif</label>
                                    <input type="radio" class="check" id="flat-radio-2{!! $i !!}"
                                           name="auth[{!! $permission->name !!}]"
                                           value="0"
                                           data-radio="iradio_flat-red" @if($permission->auth == 0) checked @endif>
                                    <label for="flat-radio-2{!! $i !!}">Pasif</label>
                                </div>
                            </div>
                            <?php $i++ ?>
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