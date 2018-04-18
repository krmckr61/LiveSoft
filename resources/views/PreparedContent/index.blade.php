@extends('layouts.app')
@section('title') Hazır İçerikler @endsection
@section('breadcrumb')
    <li class="active">Hazır İçerikler</li>
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
    <script src="/asset/plugins/bower_components/ckeditor/ckeditor.js"></script>
    <script src="/asset/plugins/bower_components/nestable/jquery.nestable.js"></script>
    <script src="/asset/pages/preparedcontent/content.js"></script>
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
                        <a href="javascript:content.add()" class="btn btn-success pull-right m-b-10">
                            <i class="fa fa-plus"></i> Yeni Ekle
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="myadmin-dd-empty dd" id="nestable">
                            <ol class="dd-list">
                                @if(count($preparedContents) > 0)
                                    @foreach($preparedContents as $content)
                                        <li class="dd-item dd3-item" data-id="{!! $content->id !!}">
                                            <div class="dd-handle dd3-handle"></div>
                                            <div class="dd3-content">
                                                <div class="row">
                                                    <div class="col-sm-8">
                                                        {!! $content->name !!}
                                                        @if($content->letter)
                                                            <code>({!! $content->letter !!} + {!! $content->number !!})</code>
                                                        @endif
                                                    </div>
                                                    <div class="col-sm-4 text-right">
                                                        <a title="Düzenle"
                                                           onclick="javascript:content.edit({!! $content->id !!})"
                                                           class="btn btn-sm btn-info"><i
                                                                    class="fa fa-pencil-square-o"></i></a>
                                                        <a title="Sil"
                                                           onclick="javascript:content.delete({!! $content->id !!})"
                                                           class="btn btn-sm btn-danger"><i
                                                                    class="fa fa-trash-o"></i></a>
                                                    </div>
                                                </div>
                                            </div>
                                            @if(isset($content['subContents']) && count($content['subContents']) > 0)
                                                <ol class="dd-list">
                                                    @foreach($content['subContents'] as $subContent)
                                                        <li class="dd-item dd3-item" data-id="{!! $subContent->id !!}">
                                                            <div class="dd-handle dd3-handle"></div>
                                                            <div class="dd3-content">
                                                                <div class="row">
                                                                    <div class="col-sm-8">
                                                                        {!! $subContent->name !!}
                                                                        @if($subContent->letter)
                                                                            <code>({!! $subContent->letter !!} + {!! $subContent->number !!})</code>
                                                                        @endif
                                                                    </div>
                                                                    <div class="col-sm-4 text-right">
                                                                        <a title="Düzenle"
                                                                           onclick="javascript:content.edit({!! $subContent->id !!})"
                                                                           class="btn btn-sm btn-info"><i
                                                                                    class="fa fa-pencil-square-o"></i></a>
                                                                        <a title="Sil"
                                                                           onclick="javascript:content.delete({!! $subContent->id !!})"
                                                                           class="btn btn-sm btn-danger"><i
                                                                                    class="fa fa-trash-o"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @if(isset($subContent['subContents']) && count($subContent['subContents']) > 0)
                                                                <ol class="dd-list">
                                                                    @foreach($subContent['subContents'] as $subContent2)
                                                                        <li class="dd-item dd3-item"
                                                                            data-id="{!! $subContent2->id !!}">
                                                                            <div class="dd-handle dd3-handle"></div>
                                                                            <div class="dd3-content">
                                                                                <div class="row">
                                                                                    <div class="col-sm-8">
                                                                                        {!! $subContent2->name !!}
                                                                                        @if($subContent2->letter)
                                                                                            <code>({!! $subContent2->letter !!} + {!! $subContent2->number !!})</code>
                                                                                        @endif
                                                                                    </div>
                                                                                    <div class="col-sm-4 text-right">
                                                                                        <a title="Düzenle"
                                                                                           onclick="javascript:content.edit({!! $subContent2->id !!})"
                                                                                           class="btn btn-sm btn-info"><i
                                                                                                    class="fa fa-pencil-square-o"></i></a>
                                                                                        <a title="Sil"
                                                                                           onclick="javascript:content.delete({!! $subContent2->id !!})"
                                                                                           class="btn btn-sm btn-danger"><i
                                                                                                    class="fa fa-trash-o"></i></a>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            @if(isset($subContent2['subContents']) && count($subContent2['subContents']) > 0)
                                                                                <ol class="dd-list">
                                                                                    @foreach($subContent2['subContents'] as $subContent3)
                                                                                        <li class="dd-item dd3-item"
                                                                                            data-id="{!! $subContent3->id !!}">
                                                                                            <div class="dd-handle dd3-handle"></div>
                                                                                            <div class="dd3-content">
                                                                                                <div class="row">
                                                                                                    <div class="col-sm-8">
                                                                                                        {!! $subContent3->name !!}
                                                                                                        @if($subContent3->letter)
                                                                                                            <code>({!! $subContent3->letter !!} + {!! $subContent3->number !!})</code>
                                                                                                        @endif
                                                                                                    </div>
                                                                                                    <div class="col-sm-4 text-right">
                                                                                                        <a title="Düzenle"
                                                                                                           onclick="javascript:content.edit({!! $subContent3->id !!})"
                                                                                                           class="btn btn-sm btn-info"><i
                                                                                                                    class="fa fa-pencil-square-o"></i></a>
                                                                                                        <a title="Sil"
                                                                                                           onclick="javascript:content.delete({!! $subContent3->id !!})"
                                                                                                           class="btn btn-sm btn-danger"><i
                                                                                                                    class="fa fa-trash-o"></i></a>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>

                                                                                            @if(isset($subContent3['subContents']) && count($subContent3['subContents']) > 0)
                                                                                                <ol class="dd-list">
                                                                                                    @foreach($subContent3['subContents'] as $subContent4)
                                                                                                        <li class="dd-item dd3-item"
                                                                                                            data-id="{!! $subContent4->id !!}">
                                                                                                            <div class="dd-handle dd3-handle"></div>
                                                                                                            <div class="dd3-content">
                                                                                                                <div class="row">
                                                                                                                    <div class="col-sm-8">
                                                                                                                        {!! $subContent4->name !!}
                                                                                                                        @if($subContent4->letter)
                                                                                                                            <code>({!! $subContent4->letter !!} + {!! $subContent3->number !!})</code>
                                                                                                                        @endif
                                                                                                                    </div>
                                                                                                                    <div class="col-sm-4 text-right">
                                                                                                                        <a title="Düzenle"
                                                                                                                           onclick="javascript:content.edit({!! $subContent4->id !!})"
                                                                                                                           class="btn btn-sm btn-info"><i
                                                                                                                                    class="fa fa-pencil-square-o"></i></a>
                                                                                                                        <a title="Sil"
                                                                                                                           onclick="javascript:content.delete({!! $subContent4->id !!})"
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
                            <form action="{!! url('preparedContents/saveOrder') !!}" method="POST">
                                {{ csrf_field() }}
                                <textarea id="nestable-output" name="contents" cols="30" rows="10"
                                          style="display: none;"></textarea>
                                <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Kaydet
                                </button>
                            </form>
                        </div>
                        <div class="alert alert-warning" id="NoContent" style="display: none;">
                            Henüz eklenmiş olan hazır içerik bulunmamaktadır.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="ContentModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true"
         style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title">İçerik Ekle/Düzenle</h4>
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
                            <label class="control-label">Tür</label>
                            <div class="col-md-12">
                                <input type="radio" class="check type-radio" id="flat-radio-3" name="type" value="head"
                                       data-radio="iradio_flat-red">
                                <label for="flat-radio-3">Başlık</label>
                                <input type="radio" class="check type-radio" id="flat-radio-4" name="type" value="text"
                                       data-radio="iradio_flat-red">
                                <label for="flat-radio-4">İçerik</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">İçerik Adı</label>
                            <input type="text" name="name" class="form-control" id="recipient-name">
                        </div>
                        <div class="form-group content">
                            <label for="recipient-name" class="control-label">İçerik Metni</label>
                            <textarea name="content" class="ckeditor" id="Text"></textarea>
                        </div>
                        <div class="form-group content">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="letter" class="control-label">Kısayol Harfi</label>
                                    <input type="text" name="letter" class="form-control text-center" id="letter">
                                </div>
                                <div class="col-sm-4 text-center tab-button-container">
                                    <img src="/asset/image/tab-button.png" alt="TAB">
                                </div>
                                <div class="col-sm-4">
                                    <label for="number" class="control-label">Kısayol Numarası</label>
                                    <input type="text" name="number" class="form-control text-center" id="number">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect" data-dismiss="modal">Kapat</button>
                    <button id="Save" type="button" onclick="javascript:content.save()"
                            class="btn btn-danger waves-effect waves-light"><i class="fa fa-check"></i> Kaydet
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection