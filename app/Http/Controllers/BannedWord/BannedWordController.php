<?php

namespace App\Http\Controllers\BannedWord;

use App\Http\Controllers\Controller;
use App\Http\Libraries\DataSeeker\DataSeeker;
use App\BannedWord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BannedWordController extends Controller
{

    private $url;

    public function __construct()
    {
        $this->middleware('permission:bannedWord');
        $this->url = 'bannedWords';
    }

    public function index()
    {
        return view('BannedWord.index');
    }

    public function add()
    {
        $bannedWord = new BannedWord();
        $bannedWord->status = '0';
        $bannedWord->save();
        return redirect(url($this->url . '/edit/' . $bannedWord->id));
    }

    public function edit($id, Request $request)
    {
        $bannedWord = BannedWord::find($id);
        if ($bannedWord) {
            return view('BannedWord.edit', ['bannedWord' => $bannedWord]);
        } else {
            $request->session()->flash('alert', ['type' => 'warning', 'message' => 'Böyle bir içerik bulunamadı.']);
            return redirect(url($this->url));
        }
    }

    public function update($id, Request $request)
    {
        $bannedWord = BannedWord::find($id);
        if ($bannedWord) {
            $active = $request->input('active');
            $content = $request->input('content');
            if ($content) {
                $bannedWord->content = $content;
                $bannedWord->active = $active;
                $bannedWord->status = 1;
                if ($bannedWord->save()) {
                    $request->session()->flash('alert', ['type' => 'success', 'message' => 'Kaydetme işlemi başarıyla gerçekleşti.']);
                    return redirect(url($this->url));
                } else {
                    $request->session()->flash('alert', ['type' => 'warning', 'message' => 'Kaydetme işlemi gerçekleştirilirken hata meydana geldi.']);
                    return redirect(url($this->url . '/edit' . $id));
                }
            } else {
                $request->session()->flash('alert', ['type' => 'warning', 'message' => 'Lütfen gerekli alanları doldurunuz.']);
                return redirect(url($this->url . '/edit' . $id));
            }
        } else {
            $request->session()->flash('alert', ['type' => 'warning', 'message' => 'Böyle bir içerik bulunamadı.']);
            return redirect(url($this->url));
        }
    }

    public function delete($id, Request $request)
    {
        $bannedWord = BannedWord::find($id);
        if ($bannedWord) {
            if ($bannedWord->delete()) {
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
        $ds->table = 'bannedword';
        $ds->fields = [
            'content' => ['type' => 'string'],
        ];
        $ds->transactions = [
            ['href' => $this->url . '/edit/{#id#}', 'color' => 'info', 'text' => 'Düzenle', 'icon' => 'fa-pencil-square-o'],
            ['href' => 'javascript:confirmation(\'Bu içeriği silmek istediğinize emin misiniz ?\', \'' . $this->url . '/delete/{#id#}\')', 'color' => 'danger', 'text' => 'Sil', 'icon' => 'fa-trash-o'],
        ];
        return $ds->getDatas($_POST);
    }

}