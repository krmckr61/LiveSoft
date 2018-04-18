<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Message;
use App\Visit;
use App\VisitUser;

class ChatController extends Controller {

    public function __construct()
    {

    }

    public function getChat($chatId)
    {
        $visit = Visit::get($chatId);
        if($visit) {
            $users = VisitUser::getUsers($chatId);
            $messages = Message::getMessages($chatId);
            $data = json_decode($visit->data, false);
            return view('Chat.index', ['users' => $users, 'messages' => $messages, 'visit' => $visit, 'data' => $data]);
        } else {
            return 'Böyle bir görüşme bulunamadı.';
        }
    }

}