<?php

namespace App\Http\Controllers\Subject;

use App\Http\Controllers\Controller;
use App\Http\Libraries\DataSeeker\DataSeeker;
use App\PermissionRole;
use App\Role;
use App\RoleUser;
use App\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SubjectController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:subject');
    }

    public function index()
    {
        return view('Subject.index');
    }

    public function add()
    {
        $user = new Subject();
        $user->status = '0';
        $user->save();
        return redirect(url('/subjects/edit/' . $user->id));
    }

    public function edit($id, Request $request)
    {
        $subject = Subject::get($id);
        if ($subject) {
            return view('Subject.edit', ['subject' => $subject]);
        } else {
            $request->session()->flash('alert', ['type' => 'warning', 'message' => 'Böyle bir konu bulunamadı.']);
            return redirect(url('/subjects'));
        }
    }

    public function update($id, Request $request)
    {
        $subject = Subject::get($id);
        if($subject) {
            $name = $request->input('name');
            $description = $request->input('description');
            $active = $request->input('active');
            if($name) {
                $subject->name = $name;
                $subject->description = $description;
                $subject->active = $active;
                $subject->status = '1';
                if($subject->save()) {
                    $request->session()->flash('alert', ['type' => 'success', 'message' => 'Kaydetme işlemi başarıyla gerçekleşti.']);
                    return redirect(url('/subjects'));
                } else {
                    $request->session()->flash('alert', ['type' => 'warning', 'message' => 'Kaydetme işlemi gerçekleştirilirken hata meydana geldi.']);
                    return redirect(url('/subjects'));
                }
            } else {
                $request->session()->flash('alert', ['type' => 'warning', 'message' => 'Lütfen gerekli alanları doldurunuz.']);
                return redirect(url('/subjects/edit/' . $id));
            }
        } else {
            $request->session()->flash('alert', ['type' => 'warning', 'message' => 'Böyle bir konu bulunamadı.']);
            return redirect(url('/subjects'));
        }

    }

    public function delete($id, Request $request)
    {
        $user = User::get($id);
        if ($user) {
            $user->status = '2';
            if ($user->save()) {
                $request->session()->flash('alert', ['type' => 'success', 'message' => 'Silme işlemi başarıyla gerçekleşti.']);
            } else {
                $request->session()->flash('alert', ['type' => 'warning', 'message' => 'Silme işlemi gerçekleştirilirken hata meydana geldi.']);
            }
        } else {
            $request->session()->flash('alert', ['type' => 'warning', 'message' => 'Böyle bir kullanıcı bulunamadı.']);
        }

        return redirect(url('/users'));
    }

    public function getDatas()
    {
        $ds = new DataSeeker();
        $ds->multipleEvent = false;
        $ds->pk = 'id';
        $ds->table = 'subject';
        $ds->fields = [
            'active' => ['type' => 'integer', 'appearance' => 'active'],
            'name' => ['type' => 'string'],
            'description' => ['type' => 'string']
        ];
        $ds->transactions = [
            ['href' => 'subjects/edit/{#id#}', 'color' => 'info', 'text' => 'Düzenle', 'icon' => 'fa-pencil-square-o'],
            ['href' => 'javascript:confirmation(\'Bu konuyu silerseniz, kullanıcılar bu konu üzerinden müşteri temsilcilerine bağlanamayacaktır. Silmek istediğinize emin misiniz ?\', \'/subjects/delete/{#id#}\')', 'color' => 'danger', 'text' => 'Sil', 'icon' => 'fa-trash-o'],
        ];
        $ds->wheres[] = ['status', '1'];
        return $ds->getDatas($_POST);
    }

}