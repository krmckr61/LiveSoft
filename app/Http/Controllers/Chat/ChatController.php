<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Message;
use App\User;
use App\Visit;
use App\VisitUser;

class ChatController extends Controller
{

    public function __construct()
    {

    }

    public function getChat($chatId)
    {
        $visit = Visit::get($chatId);
        if ($visit) {
            $users = VisitUser::getUsers($chatId);
            $messages = Message::getMessages($chatId);
            $data = json_decode($visit->data, true);

            $data['connectionTime'] = date('Y-m-d H:i:s', strtotime($visit['created_at']));
            $data['closed_at'] = date('Y-m-d H:i:s', strtotime($visit['closed_at']));
            $data['chatTime'] = secondsToHours((strtotime($data['closed_at']) - strtotime($data['connectionTime'])));

            $data['closedUserName'] = 'Ziyaretçi';
            if ($visit['active'] == 3) {
                if ($visit['closeduser'] != 0) {
                    $closedUserName = User::getName($visit['closeduser']);
                    if ($closedUserName) {
                        $data['closedUserName'] = $closedUserName;
                    }
                }
            }
            if ($data['device']) {
                $data['os'] = $data['device']['os'];
                $data['browser'] = $data['device']['browser'];
            }
            if ($data['location']) {
                $data['countryCode'] = $data['location']['countryCode'];
                $data['country'] = $data['location']['country'];
                $data['city'] = $data['location']['city'];
            }
            $data['loginType'] = ($data['FacebookId'] ? '<i class="fa fa-facebook facebook-loggedin-client-icon"></i>' : 'Normal');

            $dataNames = [
                ['text' => 'Adı Soyadı', 'field' => 'NameSurname'],
                ['text' => 'E-Posta Adresi', 'field' => 'Email'],
                ['text' => 'Kullanıcı Adı', 'field' => 'UserName'],
                ['text' => 'Bağlantı Tarihi', 'field' => 'connectionTime'],
                ['text' => 'İşletim Sistemi', 'field' => 'os'],
                ['text' => 'Tarayıcı', 'field' => 'browser'],
                ['text' => 'Ip Adresi', 'field' => 'ipAddress'],
                ['text' => 'Ülke', 'field' => 'country'],
                ['text' => 'Şehir', 'field' => 'city'],
                ['text' => 'Giriş Türü', 'field' => 'loginType'],
                ['text' => 'Bitiş Tarihi', 'field' => 'closed_at'],
                ['text' => 'Görüşme Süresi', 'field' => 'chatTime'],
                ['text' => 'Görüşmeyi Sonlandıran', 'field' => 'closedUserName'],
            ];

            return view('Chat.index', ['users' => $users, 'messages' => $messages, 'visit' => $visit, 'data' => $data, 'dataNames' => $dataNames]);
        } else {
            return 'Böyle bir görüşme bulunamadı.';
        }
    }

}