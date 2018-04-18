<?php

namespace App\Http\Controllers\BannedUser;

use App\Http\Controllers\Controller;
use App\Http\Libraries\DataSeeker\DataSeeker;
use App\BannedUser;
use App\User;
use App\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BannedUserController extends Controller
{

    private $url;

    public function __construct()
    {
        $this->middleware('permission:bannedUser');
        $this->url = 'bannedUsers';
    }

    public function index()
    {
        $users = User::select('id', 'name')->where([['active', '1'], ['status', '1']])->get();
        return view('BannedUser.index', ['users' => $users]);
    }

    public function edit($id, Request $request)
    {
        $bannedUser = BannedUser::get($id);
        if ($bannedUser) {
            $bannedUser->seen = '1';
            $bannedUser->save();
            $clientData = Visit::getBannedClientData($bannedUser->clientid, $bannedUser->created_at);
            if($clientData) {
                $clientData = json_decode($clientData->data);
            }
            return view('BannedUser.edit', ['bannedUser' => $bannedUser, 'clientData' => $clientData]);
        } else {
            $request->session()->flash('alert', ['type' => 'warning', 'message' => 'Böyle bir içerik bulunamadı.']);
            return redirect(url($this->url));
        }
    }

    public function delete($id, Request $request)
    {
        $bannedUser = BannedUser::find($id);
        if ($bannedUser) {
            if ($bannedUser->delete()) {
                $request->session()->flash('alert', ['type' => 'success', 'message' => 'Silme işlemi başarıyla gerçekleşti.']);
            } else {
                $request->session()->flash('alert', ['type' => 'warning', 'message' => 'Silme işlemi gerçekleştirilirken hata meydana geldi.']);
            }
        } else {
            $request->session()->flash('alert', ['type' => 'warning', 'message' => 'Böyle bir içerik bulunamadı.']);
        }
        return redirect(url($this->url));
    }

    public function getDatas()
    {
        $ds = new DataSeeker();
        $ds->multipleEvent = false;
        $ds->pk = 'id';
        $ds->table = 'banneduser';
        $ds->fields = [
            'seen' => ['type' => 'integer', 'appearance' => 'active', 'activeTexts' => ['Görüldü', 'Görülmedi']],
            'userid' => ['type' => 'integer', 'join' => ['table2' => 'users', 'left' => 'banneduser.userid', 'right' => 'users.id', 'show' => 'users.name']],
            'created_at' => ['type' => 'timestamp'],
            'date' => ['type' => 'timestamp'],
        ];
        $ds->transactions = [
            ['href' => $this->url . '/edit/{#id#}', 'color' => 'info', 'text' => 'İncele', 'icon' => 'fa-info'],
            ['href' => 'javascript:confirmation(\'Bu engeli kaldırmak istediğinize emin misiniz ?\', \'' . $this->url . '/delete/{#id#}\')', 'color' => 'danger', 'text' => 'Sil', 'icon' => 'fa-trash-o'],
        ];
        $ds->wheres[] = ['date', '>=', date('Y-m-d H:i', time())];
        return $ds->getDatas($_POST);
    }

}