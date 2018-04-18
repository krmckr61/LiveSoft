@extends('layouts.app')
@section('title') Hesap Ayarlarım @endsection
@section('breadcrumb')
    <li class="active"><i class="ti-user"></i> Hesap Ayarlarım</li>
@endsection
@section('js')
    <script src="/asset/plugins/bower_components/custom-select/custom-select.min.js" type="text/javascript"></script>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <form action="{!! url('profile/update') !!}" method="POST" class="form-material form-horizontal" data-toggle="validator" novalidate="true">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-md-12">Adı, Soyadı</label>
                        <div class="col-md-12">
                            <input type="text" class="form-control form-control-line" name="name"
                                   value="{!! $user->name !!}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">E-Posta Adresi</label>
                        <div class="col-md-12">
                            <input type="email" class="form-control form-control-line" name="email"
                                   value="{!! $user->email !!}" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Parola</label>
                        <div class="col-md-12">
                            <input type="password" class="form-control form-control-line" name="password"
                                   value="">
                            <span class="help-block">Parolanızı değiştirmek istemiyorsanız boş bırakınız.</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-12">Parola (Tekrar)</label>
                        <div class="col-md-12">
                            <input type="password" class="form-control form-control-line" name="password2"
                                   value="">
                            <span class="help-block">Parolanızı değiştirmek istemiyorsanız boş bırakınız.</span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success waves-effect waves-light"><i class="fa fa-check"></i>
                        Kaydet
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="ribbon-wrapper bg-white">
        <div class="ribbon ribbon-bookmark ribbon-default">Roller</div>
        <p class="ribbon-content">
            @if(count($userRoles) > 0)
                @foreach($userRoles as $key => $userRole)
                    <span class="label label-rouded label-info">
                    {!! $userRole->display_name !!}
                        @if($key + 1 != count($userRoles))
                            &nbsp;,
                        @endif
                    </span>
                @endforeach
            @endif
        </p>
    </div>
@endsection