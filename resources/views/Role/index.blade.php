@extends('layouts.app')
@section('title') Roller @endsection
@section('breadcrumb')
    <li class="active">Roller</li>
@endsection
@section('css')
    <link href="/asset/plugins/bower_components/nestable/nestable.css" rel="stylesheet" type="text/css"/>
    <style>
        #nestable {
            margin: 0 auto;
        }
    </style>
@endsection
@section('js')
    <script src="/asset/plugins/bower_components/nestable/jquery.nestable.js"></script>
    <script src="/asset/pages/role/role.js"></script>
    <script type="text/javascript">
        // Nestable
        var updateOutput = function (e) {
            var list = e.length ? e : $(e.target),
                output = list.data('output');
            if (window.JSON) {
                output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));
            } else {
                output.val('JSON browser support required for this demo.');
            }
        };
        $('#nestable').nestable({
            group: 1
        }).on('change', updateOutput);

        updateOutput($('#nestable').data('output', $('#nestable-output')));
    </script>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <div class="row">
                    <div class="col-sm-12">
                        <a href="javascript:role.add()" class="btn btn-success pull-right m-b-10">
                            <i class="fa fa-plus"></i> Yeni Ekle
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="myadmin-dd-empty dd" id="nestable">
                            <ol class="dd-list">
                                @if(count($roles) > 0)
                                    @foreach($roles as $role)
                                        <li class="dd-item dd3-item" data-id="{!! $role->id !!}">
                                            <div class="dd-handle dd3-handle"></div>
                                            <div class="dd3-content">
                                                <div class="row">
                                                    <div class="col-sm-8">
                                                        {!! $role->display_name !!}
                                                    </div>
                                                    <div class="col-sm-4 text-right">
                                                        <a title="Yetkilendirme"
                                                           href="{!! url('roles/delegation/' . $role->id) !!}"
                                                           class="btn btn-sm btn-warning"><i
                                                                    class="fa fa-lock"></i></a>
                                                        <a title="Düzenle"
                                                           onclick="javascript:role.edit({!! $role->id !!})"
                                                           class="btn btn-sm btn-info"><i
                                                                    class="fa fa-pencil-square-o"></i></a>
                                                        <a title="Sil"
                                                           onclick="javascript:role.delete({!! $role->id !!})"
                                                           class="btn btn-sm btn-danger"><i
                                                                    class="fa fa-trash-o"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            @if(isset($role['subRoles']) && count($role['subRoles']) > 0)
                                                <ol class="dd-list">
                                                    @foreach($role['subRoles'] as $subRole)
                                                        <li class="dd-item dd3-item" data-id="{!! $subRole->id !!}">
                                                            <div class="dd-handle dd3-handle"></div>
                                                            <div class="dd3-content">
                                                                <div class="row">
                                                                    <div class="col-sm-8">
                                                                        {!! $subRole->display_name !!}
                                                                    </div>
                                                                    <div class="col-sm-4 text-right">
                                                                        <a title="Yetkilendirme"
                                                                           href="{!! url('roles/delegation/' . $subRole->id) !!}"
                                                                           class="btn btn-sm btn-warning"><i
                                                                                    class="fa fa-lock"></i></a>
                                                                        <a title="Düzenle"
                                                                           onclick="javascript:role.edit({!! $subRole->id !!})"
                                                                           class="btn btn-sm btn-info"><i
                                                                                    class="fa fa-pencil-square-o"></i></a>
                                                                        <a title="Sil"
                                                                           onclick="javascript:role.delete({!! $subRole->id !!})"
                                                                           class="btn btn-sm btn-danger"><i
                                                                                    class="fa fa-trash-o"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @if(isset($subRole['subRoles']) && count($subRole['subRoles']) > 0)
                                                                <ol class="dd-list">
                                                                    @foreach($subRole['subRoles'] as $subRole2)
                                                                        <li class="dd-item dd3-item" data-id="{!! $subRole2->id !!}">
                                                                            <div class="dd-handle dd3-handle"></div>
                                                                            <div class="dd3-content">
                                                                                <div class="row">
                                                                                    <div class="col-sm-8">
                                                                                        {!! $subRole2->display_name !!}
                                                                                    </div>
                                                                                    <div class="col-sm-4 text-right">
                                                                                        <a title="Yetkilendirme"
                                                                                           href="{!! url('roles/delegation/' . $subRole2->id) !!}"
                                                                                           class="btn btn-sm btn-warning"><i
                                                                                                    class="fa fa-lock"></i></a>
                                                                                        <a title="Düzenle"
                                                                                           onclick="javascript:role.edit({!! $subRole2->id !!})"
                                                                                           class="btn btn-sm btn-info"><i
                                                                                                    class="fa fa-pencil-square-o"></i></a>
                                                                                        <a title="Sil"
                                                                                           onclick="javascript:role.delete({!! $subRole2->id !!})"
                                                                                           class="btn btn-sm btn-danger"><i
                                                                                                    class="fa fa-trash-o"></i></a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @if(isset($subRole2['subRoles']) && count($subRole2['subRoles']) > 0)
                                                                                <ol class="dd-list">
                                                                                    @foreach($subRole2['subRoles'] as $subRole3)
                                                                                        <li class="dd-item dd3-item" data-id="{!! $subRole3->id !!}">
                                                                                            <div class="dd-handle dd3-handle"></div>
                                                                                            <div class="dd3-content">
                                                                                                <div class="row">
                                                                                                    <div class="col-sm-8">
                                                                                                        {!! $subRole3->display_name !!}
                                                                                                    </div>
                                                                                                    <div class="col-sm-4 text-right">
                                                                                                        <a title="Yetkilendirme"
                                                                                                           href="{!! url('roles/delegation/' . $subRole3->id) !!}"
                                                                                                           class="btn btn-sm btn-warning"><i
                                                                                                                    class="fa fa-lock"></i></a>
                                                                                                        <a title="Düzenle"
                                                                                                           onclick="javascript:role.edit({!! $subRole3->id !!})"
                                                                                                           class="btn btn-sm btn-info"><i
                                                                                                                    class="fa fa-pencil-square-o"></i></a>
                                                                                                        <a title="Sil"
                                                                                                           onclick="javascript:role.delete({!! $subRole3->id !!})"
                                                                                                           class="btn btn-sm btn-danger"><i
                                                                                                                    class="fa fa-trash-o"></i></a>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>

                                                                                            @if(isset($subRole3['subRoles']) && count($subRole3['subRoles']) > 0)
                                                                                                <ol class="dd-list">
                                                                                                    @foreach($subRole3['subRoles'] as $subRole4)
                                                                                                        <li class="dd-item dd3-item" data-id="{!! $subRole4->id !!}">
                                                                                                            <div class="dd-handle dd3-handle"></div>
                                                                                                            <div class="dd3-content">
                                                                                                                <div class="row">
                                                                                                                    <div class="col-sm-8">
                                                                                                                        {!! $subRole4->display_name !!}
                                                                                                                    </div>
                                                                                                                    <div class="col-sm-4 text-right">
                                                                                                                        <a title="Yetkilendirme"
                                                                                                                           href="{!! url('roles/delegation/' . $subRole4->id) !!}"
                                                                                                                           class="btn btn-sm btn-warning"><i
                                                                                                                                    class="fa fa-lock"></i></a>
                                                                                                                        <a title="Düzenle"
                                                                                                                           onclick="javascript:role.edit({!! $subRole4->id !!})"
                                                                                                                           class="btn btn-sm btn-info"><i
                                                                                                                                    class="fa fa-pencil-square-o"></i></a>
                                                                                                                        <a title="Sil"
                                                                                                                           onclick="javascript:role.delete({!! $subRole4->id !!})"
                                                                                                                           class="btn btn-sm btn-danger"><i
                                                                                                                                    class="fa fa-trash-o"></i></a>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </li>
                                                                                                    @endforeach
                                                                                                </ol>
                                                                                            @endif
                                                                                        </li>
                                                                                    @endforeach
                                                                                </ol>
                                                                            @endif
                                                                        </li>
                                                                    @endforeach
                                                                </ol>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ol>
                                            @endif
                                        </li>
                                    @endforeach
                                @endif
                            </ol>
                        </div>
                        <div class="col-xs-12 text-center m-t-20">
                            <form action="{!! url('roles/saveOrder') !!}" method="POST">
                                {{ csrf_field() }}
                                <textarea id="nestable-output" name="roles" cols="30" rows="10"
                                          style="display: none;"></textarea>
                                <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Kaydet
                                </button>
                            </form>
                        </div>
                        <div class="alert alert-warning" id="NoRole" style="display: none;">
                            Henüz eklenmiş olan kullanıcı rolü bulunmamaktadır.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="RoleModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
         style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">Rol Ekle/Düzenle</h4>
                </div>
                <div class="modal-body">
                    <form action="javascript:;">
                        <input type="hidden" name="id" value="">
                        <div class="form-group">
                            <label class="control-label">Aktiflik</label>
                            <div class="col-md-12">
                                <input type="radio" class="check" id="flat-radio-1" name="active" value="1"
                                       data-radio="iradio_flat-red">
                                <label for="flat-radio-1">Aktif</label>
                                <input type="radio" class="check" id="flat-radio-2" name="active" value="0"
                                       data-radio="iradio_flat-red">
                                <label for="flat-radio-2">Pasif</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">Rol Adı</label>
                            <input type="text" name="display_name" class="form-control" id="recipient-name">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Kapat</button>
                    <button id="Save" type="button" onclick="javascript:role.save()"
                            class="btn btn-danger waves-effect waves-light"><i class="fa fa-check"></i> Kaydet
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection