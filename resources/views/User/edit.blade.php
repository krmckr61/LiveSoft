@extends('layouts.app')
@section('title') Personel Düzenle @endsection
@section('breadcrumb')
    <li><a href="{!! url('users') !!}">Personeller</a></li>
    <li class="active"><i class="fa fa-pencil-square-o"></i> Düzenle</li>
@endsection
@section('js')
    <script src="/asset/plugins/bower_components/custom-select/custom-select.min.js" type="text/javascript"></script>
    <script src="/asset/pages/user/edit.js" type="text/javascript"></script>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <form action="{!! url('users/update/' . $user->id) !!}" method="POST"
                      class="form-material form-horizontal" data-toggle="validator" novalidate="true">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="col-md-12">Aktiflik</label>
                        <div class="col-md-12">
                            <input type="radio" class="check" id="flat-radio-1" name="active" value="1"
                                   data-radio="iradio_flat-red"{!! (($user->active == 1) ? ' checked' : '') !!}>
                            <label for="flat-radio-1">Aktif</label>
                            <input type="radio" class="check" id="flat-radio-2" name="active" value="0"
                                   data-radio="iradio_flat-red"{!! (($user->active == 0) ? ' checked' : '') !!}>
                            <label for="flat-radio-2">Pasif</label>
                        </div>
                    </div>
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
                            <input type="text" class="form-control form-control-line" name="password"
                                   value=""{!! (($user->status == '0') ? ' required' : '') !!}>
                        </div>
                    </div>
                    @if($user->can('liveSupport'))
                        <div class="form-group">
                            <label class="col-md-12">Anlık Alabileceği Maksimum Müşteri Sayısı</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control form-control-line" name="maxvisitcount"
                                       value="{!! $user->maxvisitcount !!}" required>
                            </div>
                        </div>
                    @endif
                    <div class="form-group">
                        <label class="col-md-12">Kullanıcı Rolleri</label>
                        <div class="col-md-12">
                            <select class="select2 m-b-10 select2-multiple" id="RoleId" multiple="multiple"
                                    data-placeholder="Seçiniz" name="RoleId[]" required>
                                @if(count($roles) > 0)
                                    @foreach($roles as $role)
                                        <option value="{!! $role->id !!}"{!! ((in_array($role->id, $userRoles)) ? ' selected' : '') !!}>{!! $role->display_name !!}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    @if(isset($subjects))
                    <div class="form-group">
                        <label class="col-md-12">Uzmanlık Konuları</label>
                        <div class="col-md-12">
                            <select class="select2 m-b-10 select2-multiple" multiple="multiple"
                                    data-placeholder="Seçiniz" name="SubjectId[]">
                                @if(count($subjects) > 0)
                                    @foreach($subjects as $subject)
                                        <option value="{!! $subject->id !!}"{!! ((in_array($subject->id, $subjectsOfUser)) ? ' selected' : '') !!}>{!! $subject->name !!}</option>
                                    @endforeach
                                @endif
                            </select>
                            <small id="fileHelp" class="form-text text-muted">Müşteri temsilcilerine bağlanan kullanıcıların görüşme konuları.</small>
                        </div>
                    </div>
                    @endif
                    <button type="submit" class="btn btn-success waves-effect waves-light"><i class="fa fa-check"></i>
                        Kaydet
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection