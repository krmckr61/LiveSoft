@extends('layouts.app')
@section('title') {!! $role->display_name . ' Mesaileri' !!} @endsection
@section('breadcrumb')
    <li><a href="{!! url('shifts') !!}">Mesailer</a></li>
    <li class="active"><i class="fa fa-pencil-square-o"></i> Düzenle</li>
@endsection
@section('css')
    <link href="{!! url('asset/plugins/bower_components/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') !!}"
          rel="stylesheet" type="text/css"/>
    <link href="{!! url('/asset/plugins/bower_components/calendar/dist/fullcalendar.css') !!}" rel="stylesheet"/>
@endsection
@section('js')
    <script src="/asset/plugins/bower_components/custom-select/custom-select.min.js" type="text/javascript"></script>
    <script src="{!! url('/asset/plugins/bower_components/moment/moment.js') !!}"></script>
    <script src="{!! url('asset/plugins/bower_components/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') !!}"></script>
    <script src="{!! url('/asset/plugins/bower_components/calendar/dist/fullcalendar.min.js') !!}"></script>
    <script src="{!! url('/asset/plugins/bower_components/calendar/dist/lang/tr.js') !!}"></script>
    <script src="{!! url('asset/plugins/bower_components/html2canvas/html2canvas.min.js') !!}"></script>
    <script src="{!! url('/asset/pages/shift/shift.js') !!}"></script>
@endsection
@section('content')
    <textarea id="Events" style="display: none;">{!! json_encode($shifts) !!}</textarea>
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <button id="Download" class="btn btn-success"><i class="fa fa-image"></i> Resim Olarak İndir</button>
                <div id="Shift"></div>
            </div>
        </div>
    </div>
    <div id="ShiftModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn btn-danger waves-effect removeButton pull-right edit-action"><i class="fa fa-trash"></i> Bu Mesaiyi Sil</button>
                    <h4 class="modal-title">Mesai Ekle/Düzenle</h4>
                </div>
                <form action="javascript:;" class="form-material form-horizontal" data-toggle="validator"
                      novalidate="true">
                    <div class="modal-body">
                        <input type="hidden" name="id">
                        <textarea name="roleid" style="display: none">{!! $role->id !!}</textarea>
                        <div class="form-group">
                            <label>Kullanıcılar</label>
                            <select class="select2 m-b-10 select2-multiple" multiple="multiple"
                                    data-placeholder="Seçiniz" name="UserIds[]" required>
                                @if(count($users) > 0)
                                    @foreach($users as $user)
                                        <option value="{!! $user->id !!}">{!! $user->name !!}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="dpicker">Mesai Başlangıç Tarihi</label>
                            <input type="text" name="startdate" id="dpicker" class="form-control datepicker-with-time"
                                   required>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="worktime">Mesai Süresi (Saat)</label>
                                    <input type="text" name="worktime" id="worktime" class="form-control text-center" data-value="{!! $workTime !!}" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="breaktime">Mola Süresi (Dakika)</label>
                                    <input type="text" name="breaktime" id="breaktime" class="form-control text-center" data-value="{!! $breakTime !!}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Kapat</button>
                        <button id="Save" type="submit"
                                class="btn btn-danger waves-effect waves-light edit-action"><i class="fa fa-check"></i> Kaydet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="OffUsersModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><span class="date"></span> Tarihinde İzinli Olanlar</h4>
                </div>
                <ul>
                </ul>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Kapat</button>
                </div>
            </div>
        </div>
    </div>

@endsection