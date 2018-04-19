<?php

namespace App\Http\Controllers\User;

use App\Config;
use App\Http\Controllers\Controller;
use App\Http\Libraries\DataSeeker\DataSeeker;
use App\PermissionRole;
use App\Role;
use App\RoleUser;
use App\Subject;
use App\User;
use App\UserSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:user');
    }

    public function index()
    {
        return view('User.index');
    }

    public function add()
    {
        $user = new User();
        $user->status = '0';
        $user->save();
        return redirect(url('/users/edit/' . $user->id));
    }

    public function edit($id, Request $request)
    {
        if ($id == Auth::user()->id) {
            return redirect(url('users'));
        }
        $user = User::get($id);
        if ($user) {
            $roles = Role::getRoles();
            $userRoles = $user->roles()->get();
            $rolesOfUser = [];
            if (count($userRoles) > 0) {
                foreach ($userRoles as $userRole) {
                    $rolesOfUser[] = $userRole->id;
                }
            }

            if(Config::getConfig('subject') && Auth::user()->can('subject')) {
                $userSubjects = UserSubject::getSubjectsFromUserId($id);
                $subjectsOfUser = [];

                if(count($userSubjects) > 0) {
                    foreach($userSubjects as $userSubject) {
                        $subjectsOfUser[] = $userSubject->subjectid;
                    }
                }

                $subjects = Subject::getAll();
                return view('User.edit', ['user' => $user, 'roles' => $roles, 'userRoles' => $rolesOfUser, 'subjects' => $subjects, 'subjectsOfUser' => $subjectsOfUser]);
            } else {
                return view('User.edit', ['user' => $user, 'roles' => $roles, 'userRoles' => $rolesOfUser]);
            }
        } else {
            $request->session()->flash('alert', ['type' => 'warning', 'message' => 'Böyle bir kullanıcı bulunamadı.']);
            return redirect(url('/users'));
        }
    }

    public function update($id, Request $request)
    {
        $user = User::get($id);
        if ($user) {
            $roles = $request->input('RoleId');
            $name = $request->input('name');
            $email = $request->input('email');
            $active = $request->input('active');
            $password = $request->input('password');
            if ($user->can('liveSupport')) {
                $maxVisitCount = $request->input('maxvisitcount');
            } else {
                $maxVisitCount = false;
            }

            if ($name && $email && ($active || $active === '0') && count($roles) > 0 && ($password || $user->status == '1') && (!$maxVisitCount || (is_numeric($maxVisitCount) && $maxVisitCount > 0))) {
                RoleUser::removeAllRoles($user->id);
                foreach ($roles as $role) {
                    $role = Role::get($role);
                    $user->roles()->attach($role);
                }
                $user->name = $name;
                $user->email = $email;
                $user->active = $active;
                if ($maxVisitCount) {
                    $user->maxvisitcount = $maxVisitCount;
                }
                if ($password) {
                    $user->password = Hash::make($password);
                }
                $user->status = '1';

                if(Config::getConfig('subject') && Auth::user()->can('subject')) {
                    $subjectIds = $request->input('SubjectId');
                    UserSubject::removeFromUserId($id);
                    if($subjectIds && count($subjectIds) > 0) {
                        foreach($subjectIds as $subjectId) {
                            $userSubject = new UserSubject();
                            $userSubject->userid = $id;
                            $userSubject->subjectid = $subjectId;
                            $userSubject->save();
                        }
                    }
                }


                if ($user->save()) {
                    $request->session()->flash('alert', ['type' => 'success', 'message' => 'Kaydetme işlemi başarıyla gerçekleşti.']);
                } else {
                    $request->session()->flash('alert', ['type' => 'warning', 'message' => 'Kaydetme işlemi gerçekleştirilirken hata meydana geldi.']);
                }

                return redirect(url('/users'));
            } else {
                $request->session()->flash('alert', ['type' => 'warning', 'message' => 'Lütfen alanları eksiksiz doldurunuz.']);
                return redirect(url('/users/edit/' . $id));
            }
        } else {
            $request->session()->flash('alert', ['type' => 'warning', 'message' => 'Böyle bir kullanıcı bulunamadı.']);
            return redirect(url('/users'));
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
        $ds->table = 'users';
        $ds->fields = [
            'active' => ['type' => 'integer', 'appearance' => 'active'],
            'name' => ['type' => 'string'],
            'email' => ['type' => 'string']
        ];
        $ds->transactions = [
            ['href' => 'users/edit/{#id#}', 'color' => 'info', 'text' => 'Düzenle', 'icon' => 'fa-pencil-square-o'],
            ['href' => 'javascript:confirmation(\'Bu kullanıcıyı silmek istediğinize emin misiniz ?\', \'/users/delete/{#id#}\')', 'color' => 'danger', 'text' => 'Sil', 'icon' => 'fa-trash-o'],
        ];
        $ds->wheres[] = ['status', '1'];
        $ds->wheres[] = ['id', '!=', Auth::user()->id];
        return $ds->getDatas($_POST);
    }

}