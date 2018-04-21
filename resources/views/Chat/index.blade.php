@extends('layouts.app')
@section('title') Görüşmeler @endsection
@section('js')
    <script type="text/javascript" src="/asset/pages/chat/index.js"></script>
@endsection
@section('content')
    <div class="white-box static-chat-container">
        <div class="row">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-6 messages">
                        @if(count($messages) > 0)
                            @foreach($messages as $message)
                                <?php
                                if ($message->sender == '2') {
                                    $senderName = $users[$message->userid];
                                }
                                ?>
                                @switch($message->sender)
                                    @case('2')
                                    <div class="message-container{!! ($message->private == '1' ? ' me private-message' : ($message->userid == Auth::user()->id ? ' me' : ' me other-user')) !!}">
                                        <div class="text">
                                            {!! $message->text !!}
                                        </div>
                                        <div class="detail send-time">{!! date('Y-m-d H:i', strtotime($message->created_at)) !!}</div>
                                        <div class="detail sender-name">{!! $senderName !!}</div>
                                    </div>
                                    @break
                                    @case('1')
                                    <div class="message-container">
                                        <div class="text">
                                            {!! $message->text !!}
                                        </div>
                                        <div class="detail send-time">{!! date('Y-m-d H:i', strtotime($message->created_at)) !!}</div>
                                        <div class="detail sender-name">{!! $data['NameSurname'] !!}</div>
                                    </div>
                                    @break
                                    @case('0')
                                    <div class="message-container system">
                                        <div class="text">
                                            {!! $message->text !!}
                                        </div>
                                        <div class="detail send-time">{!! date('Y-m-d H:i', strtotime($message->created_at)) !!}</div>
                                    </div>
                                    @break
                                @endswitch
                            @endforeach
                        @endif
                    </div>
                    <div class="col-sm-3">
                        <div class="row">
                            <div class="col-sm-12 client-infos">
                                @foreach($dataNames as $dataName)
                                    @if(isset($data[$dataName['field']]))
                                        <div class="client-detail-row col-sm-12">
                                            <div class="name">{!! $dataName['text'] !!}</div>
                                            <div class="value">{!! $data[$dataName['field']] !!}</div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="row">
                            <div class="col-sm-12 client-infos">
                                <div class="client-detail-row col-sm-12">
                                    <div class="name">Görüşme Puanı</div>
                                    <div class="value">{!! $visit->point ? $visit->point : 'N/A' !!}</div>
                                </div>
                                <div class="client-detail-row col-sm-12">
                                    <div class="name">Görüşmedeki Temsilciler</div>
                                    <div class="value">
                                        @if(count($users))
                                            <?php $i = 0; ?>
                                            @foreach($users as $user)
                                                @if($i != 0)
                                                    ,
                                                @endif
                                                {!! $user !!}
                                                <?php $i++ ?>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection