<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Permission;
use App\PermissionRole;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Zizaco\Entrust\Entrust;

class RoleController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:role');
    }

    public function index()
    {
        $roles = [];
        $topId = NULL;

        $topRoles = Role::getFromTopId($topId);
        if (count($topRoles) > 0) {
            $roles = $topRoles;
            foreach ($roles as $key => $role) {
                $sub1Roles = Role::getFromTopId($role->id);
                if (count($sub1Roles) > 0) {
                    $role['subRoles'] = $sub1Roles;
                    foreach ($role['subRoles'] as $sub1Role) {
                        $sub2Roles = Role::getFromTopId($sub1Role->id);
                        if (count($sub2Roles) > 0) {
                            $sub1Role['subRoles'] = $sub2Roles;
                            foreach ($sub1Role['subRoles'] as $sub2Role) {
                                $sub3Roles = Role::getFromTopId($sub2Role->id);
                                if (count($sub3Roles) > 0) {
                                    $sub2Role['subRoles'] = $sub3Roles;
                                    foreach ($sub2Role['subRoles'] as $sub3Role) {
                                        $sub4Roles = Role::getFromTopId($sub3Role->id);
                                        if (count($sub4Roles) > 0) {
                                            $sub3Role['subRoles'] = $sub4Roles;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return view('Role.index', ['roles' => $roles]);
    }

    public function add()
    {
        $role = new Role();
        $role->status = '0';
        $role->save();
        return response()->json(['type' => 'success', 'role' => $role]);
    }

    public function get($id)
    {
        if($this->canEdit($id)) {
            $role = Role::get($id);
            if ($role) {
                $return = ['type' => 'success', 'role' => $role];
            } else {
                $return = ['type' => 'error'];
            }
        } else {
            $return = ['type' => 'warning', 'message' => 'Bu rolün yönetimi için gereken yetkiye sahip değilsiniz.'];
        }
        return response()->json($return);
    }

    public function update($id, Request $request)
    {
        if($this->canEdit($id)) {
            $role = Role::get($id);
            if ($role) {
                $displayName = $request->input('display_name');
                if (!Role::getFromDisplayName($displayName)) {
                    $role->display_name = $displayName;
                    $role->active = $request->input('active');

                    if ($role->status == '0') {
                        $role->name = str_slug($role->display_name);
                    }
                    $role->status = '1';
                    if ($role->save()) {
                        $return = ['type' => 'success'];
                    } else {
                        $return = ['type' => 'error'];
                    }
                } else {
                    $return = ['type' => 'warning', 'message' => 'Bu rol adı daha önceden sisteme zaten kaydedilmiş.'];
                }
            } else {
                $return = ['type' => 'error'];
            }
        } else {
            $return = ['type' => 'warning', 'message' => 'Bu rolün yönetimi için gereken yetkiye sahip değilsiniz.'];
        }

        return response()->json($return);
    }

    public function delete($id)
    {
        if($this->canEdit($id)) {
            $role = Role::get($id);
            if ($role) {
                $role->status = '2';
                if ($role->save()) {
                    $return = ['type' => 'success'];
                } else {
                    $return = ['type' => 'error'];
                }
            } else {
                $return = ['type' => 'error'];
            }
        } else {
            $return = ['type' => 'warning', 'message' => 'Bu rolün yönetimi için gereken yetkiye sahip değilsiniz.'];
        }

        return response()->json($return);
    }

    public function saveOrder(Request $request)
    {
        $roles = $request->input('roles');
        $roles = json_decode($roles, 1);
        if (is_array($roles) && count($roles)) {
            foreach ($roles as $category) {
                $cat = Role::findOrFail($category['id']);
                $cat->top_id = NULL;
                $cat->save();
                if (isset($category['children']) && is_array($category['children'])) {
                    foreach ($category['children'] as $category1) {
                        $cat1 = Role::find($category1['id']);
                        $cat1->top_id = $category['id'];
                        $cat1->save();
                        if (isset($category1['children']) && is_array($category1['children'])) {
                            foreach ($category1['children'] as $category2) {
                                $cat2 = Role::findOrFail($category2['id']);
                                $cat2->top_id = $category1['id'];
                                $cat2->save();
                                if (isset($category2['children']) && is_array($category2['children'])) {
                                    foreach ($category2['children'] as $category3) {
                                        $cat3 = Role::findOrFail($category3['id']);
                                        $cat3->top_id = $category2['id'];
                                        $cat3->save();
                                        if (isset($category3['children']) && is_array($category3['children'])) {
                                            foreach ($category3['children'] as $category4) {
                                                $cat4 = Role::findOrFail($category4['id']);
                                                $cat4->top_id = $category3['id'];
                                                $cat4->save();
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $request->session()->flash('alert', ['type' => 'success', 'message' => 'Kaydetme işlemi başarıyla gerçekleşti.']);
        return redirect(url('roles'));
    }

    public function delegation($id, Request $request)
    {
        if($this->canEdit($id)) {
            $role = Role::get($id);
            if ($role) {
                if(Auth::user()->hasRole($role->name)) {
                    $permissions = Permission::getAllWithoutRole();
                } else {
                    $permissions = Permission::getAll();
                }
                if (count($permissions) > 0) {
                    foreach ($permissions as $key => $permission) {
                        if (!Auth::user()->can($permission->name) && !$this->isTop()) {
                            unset($permissions[$key]);
                        } else {
                            if (PermissionRole::hasPerm($id, $permission->id)) {
                                $permissions[$key]['auth'] = 1;
                            } else {
                                $permissions[$key]['auth'] = 0;
                            }
                        }
                    }
                }
                return view('Role.delegation', ['role' => $role, 'permissions' => $permissions]);
            } else {
                $request->session()->flash('alert', ['type' => 'warning', 'message' => 'Böyle bir rol bulunamadı.']);
                return redirect(url('/roles'));
            }
        } else {
            $request->session()->flash('alert', ['type' => 'warning', 'message' => 'Bu rolün yönetimi için gereken yetkiye sahip değilsiniz.']);
            return redirect(url('/roles'));
        }

    }

    public function delegationUpdate($id, Request $request)
    {
        if($this->canEdit($id)) {
            $role = Role::get($id);
            if ($role) {
                $authes = $request->input('auth');
                if (count($authes) > 0) {
                    foreach ($authes as $key => $auth) {
                        $permission = Permission::getFromName($key);
                        if ($auth == 1) {
                            if (!PermissionRole::hasPerm($id, $permission->id)) {
                                $role->perms()->attach($permission);
                            }
                        } else {
                            if (PermissionRole::hasPerm($id, $permission->id)) {
                                $role->perms()->detach($permission);
                            }
                        }
                    }
                }

                $request->session()->flash('alert', ['type' => 'success', 'message' => 'Yetkilendirme işlemi başarıyla gerçekleşti.']);
                return redirect(url('/roles'));


            } else {
                $request->session()->flash('alert', ['type' => 'warning', 'message' => 'Böyle bir rol bulunamadı.']);
                return redirect(url('/roles'));
            }
        } else {
            $request->session()->flash('alert', ['type' => 'warning', 'message' => 'Bu rolün yönetimi için gereken yetkiye sahip değilsiniz.']);
            return redirect(url('/roles'));
        }
    }

    public function canEdit($id)
    {
        if($this->isTop()) {
            return true;
        }

        if ($this->isEqual($id)) {
            return false;
        } else {
            $roles = Auth::user()->roles()->get();
            if (count($roles) > 0) {
                foreach ($roles as $role) {
                    $subRoles = Role::where([['top_id', $role->id], ['active', '1'], ['status', '1']])->get();
                    if (count($subRoles) > 0) {
                        foreach ($subRoles as $subRole) {
                            if ($subRole->id == $id) {
                                return true;
                            }
                        }
                        foreach ($subRoles as $subRole) {
                            $subRoles2 = Role::where([['top_id', $subRole->id], ['active', '1'], ['status', '1']])->get();
                            if (count($subRoles2) > 0) {
                                foreach ($subRoles2 as $subRole2) {
                                    if ($subRole2->id == $id) {
                                        return true;
                                    }
                                }
                                foreach ($subRoles2 as $subRole2) {
                                    $subRoles3 = Role::where([['top_id', $subRole2->id], ['active', '1'], ['status', '1']])->get();
                                    if (count($subRoles3) > 0) {
                                        foreach ($subRoles3 as $subRole3) {
                                            if ($subRole3->id == $id) {
                                                return true;
                                            }
                                        }
                                        foreach ($subRoles3 as $subRole3) {
                                            $subRoles4 = Role::where([['top_id', $subRole3->id], ['active', '1'], ['status', '1']])->get();
                                            if (count($subRoles4) > 0) {
                                                foreach ($subRoles4 as $subRole4) {
                                                    if ($subRole4->id == $id) {
                                                        return true;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            return false;
        }
    }

    public function isEqual($id)
    {
        $roles = Auth::user()->roles()->get();
        if (count($roles) > 0) {
            foreach ($roles as $role) {
                if (Role::where([['id', $id], ['top_id', $role->top_id], ['active', '1'], ['status', '1']])->first()) {
                    return true;
                }
            }
            return false;
        } else {
            return false;
        }
    }

    public function isTop() {
        $roles = Auth::user()->roles()->get();
        if (count($roles) > 0) {
            foreach ($roles as $role) {
                if($role->top_id == NULL) {
                    return true;
                }
            }
        }
    }

}