<?php

namespace App\Http\Controllers\WelcomeMessage;

use App\Http\Controllers\Controller;
use App\Http\Libraries\DataSeeker\DataSeeker;
use App\WelcomeMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WelcomeMessageController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:welcomeMessage');
    }

    public function index()
    {
        return view('WelcomeMessage.index');
    }

    public function edit($id, Request $request)
    {
        $message = WelcomeMessage::find($id);
        if ($message) {
            return view('WelcomeMessage.edit', ['message' => $message]);
        } else {
            $request->session()->flash('alert', ['type' => 'warning', 'message' => 'Böyle bir karşılama mesajı bulunamadı.']);
            return redirect(url('welcomeMessages'));
        }
    }

    public function update($id, Request $request)
    {
        $message = WelcomeMessage::find($id);
        if ($message) {
            $content = $request->input('content');
            if($content) {
                $message->content = $content;
                if($message->save()) {
                    $request->session()->flash('alert', ['type' => 'success', 'message' => 'Kaydetme işlemi başarıyla gerçekleşti.']);
                    return redirect(url('welcomeMessages'));
                } else {
                    $request->session()->flash('alert', ['type' => 'warning', 'message' => 'Kaydetme işlemi gerçekleştirilirken hata meydana geldi.']);
                    return redirect(url('welcomeMessages/edit' . $id));
                }
            } else {
                $request->session()->flash('alert', ['type' => 'warning', 'message' => 'Mesaj içeriği boş geçilemez.']);
                return redirect(url('welcomeMessages/edit' . $id));
            }
        } else {
            $request->session()->flash('alert', ['type' => 'warning', 'message' => 'Böyle bir karşılama mesajı bulunamadı.']);
            return redirect(url('welcomeMessages'));
        }
    }

    public function getDatas()
    {
        $ds = new DataSeeker();
        $ds->multipleEvent = false;
        $ds->pk = 'id';
        $ds->table = 'welcomemessage';
        $ds->fields = [
            'name' => ['type' => 'string'],
            'content' => ['type' => 'string']
        ];
        $ds->transactions = [
            ['href' => 'welcomeMessages/edit/{#id#}', 'color' => 'info', 'text' => 'Düzenle', 'icon' => 'fa-pencil-square-o'],
        ];
        return $ds->getDatas($_POST);
    }

}