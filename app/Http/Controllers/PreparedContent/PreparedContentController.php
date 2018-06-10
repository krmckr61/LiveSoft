<?php

namespace App\Http\Controllers\PreparedContent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\PreparedContent;
use Illuminate\Support\Facades\Auth;

class PreparedContentController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:preparedContent');
    }

    public function index()
    {
        $preparedContents = [];
        $topId = NULL;

        $topContents = PreparedContent::getFromTopId($topId);
        if (count($topContents) > 0) {
            $preparedContents = $topContents;
            foreach ($preparedContents as $key => $content) {
                $sub1Contents = PreparedContent::getFromTopId($content->id);
                if (count($sub1Contents) > 0) {
                    $content['subContents'] = $sub1Contents;
                    foreach ($content['subContents'] as $sub1Content) {
                        $sub2Contents = PreparedContent::getFromTopId($sub1Content->id);
                        if (count($sub2Contents) > 0) {
                            $sub1Content['subContents'] = $sub2Contents;
                            foreach ($sub1Content['subContents'] as $sub2Content) {
                                $sub3Contents = PreparedContent::getFromTopId($sub2Content->id);
                                if (count($sub3Contents) > 0) {
                                    $sub2Content['subContents'] = $sub3Contents;
                                    foreach ($sub2Content['subContents'] as $sub3Content) {
                                        $sub4Contents = PreparedContent::getFromTopId($sub3Content->id);
                                        if (count($sub4Contents) > 0) {
                                            $sub3Content['subContents'] = $sub4Contents;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return view('PreparedContent.index', ['preparedContents' => $preparedContents]);
    }

    public static function getContentsToJson()
    {
        $preparedContents = [];
        $topId = NULL;

        $topContents = PreparedContent::getFromTopId($topId);
        if (count($topContents) > 0) {
            $preparedContents = $topContents;
            foreach ($preparedContents as $key => $content) {
                $content->text = $content->name;
                if($content->letter) {
                    $content->text .= '<code>' . $content->letter . ' + ' . $content->number  . '</code>';
                }
                $sub1Contents = PreparedContent::getFromTopId($content->id);
                if (count($sub1Contents) > 0) {
                    $content['nodes'] = $sub1Contents;
                    foreach ($content['nodes'] as $sub1Content) {
                        $sub1Content->text = $sub1Content->name;
                        if($sub1Content->letter) {
                            $sub1Content->text .= '<code>' . $sub1Content->letter . ' + ' . $sub1Content->number  . '</code>';
                        }
                        $sub2Contents = PreparedContent::getFromTopId($sub1Content->id);
                        if (count($sub2Contents) > 0) {
                            $sub1Content['nodes'] = $sub2Contents;
                            foreach ($sub1Content['nodes'] as $sub2Content) {
                                $sub2Content->text = $sub2Content->name;
                                if($sub2Content->letter) {
                                    $sub2Content->text .= '<code>' . $sub2Content->letter . ' + ' . $sub2Content->number  . '</code>';
                                }
                                $sub3Contents = PreparedContent::getFromTopId($sub2Content->id);
                                if (count($sub3Contents) > 0) {
                                    $sub2Content['nodes'] = $sub3Contents;
                                    foreach ($sub2Content['nodes'] as $sub3Content) {
                                        $sub3Content->text = $sub3Content->name;
                                        if($sub3Content->letter) {
                                            $sub3Content->text .= '<code>' . $sub3Content->letter . ' + ' . $sub3Content->number  . '</code>';
                                        }
                                        $sub4Contents = PreparedContent::getFromTopId($sub3Content->id);
                                        if (count($sub4Contents) > 0) {
                                            $sub3Content['nodes'] = $sub4Contents;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return json_encode($preparedContents, JSON_HEX_QUOT | JSON_HEX_TAG);

    }

    public function add()
    {
        $content = new PreparedContent();
        $content->status = '0';
        $content->type = 'head';
        $content->active = '1';
        $content->save();
        return response()->json(['type' => 'success', 'content' => $content]);
    }

    public function get($id)
    {
        $content = PreparedContent::get($id);
        if ($content) {
            $return = ['type' => 'success', 'content' => $content];
        } else {
            $return = ['type' => 'error'];
        }
        return response()->json($return);
    }

    public function update($id, Request $request)
    {
        $preparedContent = PreparedContent::where('id', $id)->first();
        if ($preparedContent) {
            $active = $request->input('active');
            $type = $request->input('type');
            $name = $request->input('name');
            $content = $request->input('content');
            $letter = $request->input('letter');
            $number = $request->input('number');
            if ($active && $type && $name && ((!$letter && !$number) || (($number || $number == 0) && $letter && !is_numeric($letter) && strlen($letter) == 1 && is_numeric($number)))) {
                if($letter && $number) {
                    if(PreparedContent::hasShortCut($id, $letter, $number)) {
                        $return = ['type' => 'warning', 'message' => 'Bu kısayol tuşu başka bir içerikte zaten mevcut.'];
                        return response()->json($return);
                    }
                }
                $preparedContent->active = $active;
                $preparedContent->type = $type;
                $preparedContent->name = $name;
                $preparedContent->content = $content;
                $preparedContent->letter = $letter;
                $preparedContent->number = $number;
                $preparedContent->status = '1';
                if ($preparedContent->save()) {
                    $return = ['type' => 'success'];
                } else {
                    $return = ['type' => 'warning', 'message' => 'Kaydetme işlemi sırasında hata meydana geldi..'];
                }
            } else {
                $return = ['type' => 'warning', 'message' => 'Lütfen gerekli alanların tamamını doldurunuz.'];
            }
        } else {
            $return = ['type' => 'warning', 'message' => 'Kaydetme işlemi sırasında hata meydana geldi....'];
        }

        return response()->json($return);
    }

    public function delete($id)
    {
        $content = PreparedContent::get($id);
        if ($content) {
            $content->status = '2';
            if ($content->save()) {
                $return = ['type' => 'success'];
            } else {
                $return = ['type' => 'error'];
            }
        } else {
            $return = ['type' => 'error'];
        }

        return response()->json($return);
    }

    public function saveOrder(Request $request)
    {
        $contents = $request->input('contents');
        $contents = json_decode($contents, 1);
        if (is_array($contents) && count($contents)) {
            foreach ($contents as $category) {
                $cat = PreparedContent::findOrFail($category['id']);
                $cat->topid = NULL;
                $cat->save();
                if (isset($category['children']) && is_array($category['children'])) {
                    foreach ($category['children'] as $category1) {
                        $cat1 = PreparedContent::find($category1['id']);
                        $cat1->topid = $category['id'];
                        $cat1->save();
                        if (isset($category1['children']) && is_array($category1['children'])) {
                            foreach ($category1['children'] as $category2) {
                                $cat2 = PreparedContent::findOrFail($category2['id']);
                                $cat2->topid = $category1['id'];
                                $cat2->save();
                                if (isset($category2['children']) && is_array($category2['children'])) {
                                    foreach ($category2['children'] as $category3) {
                                        $cat3 = PreparedContent::findOrFail($category3['id']);
                                        $cat3->topid = $category2['id'];
                                        $cat3->save();
                                        if (isset($category3['children']) && is_array($category3['children'])) {
                                            foreach ($category3['children'] as $category4) {
                                                $cat4 = PreparedContent::findOrFail($category4['id']);
                                                $cat4->topid = $category3['id'];
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
        return redirect(url('preparedContents'));
    }

    public function delegation($id, Request $request)
    {
        if ($this->canEdit($id)) {
            $role = Role::get($id);
            if ($role) {
                if (Auth::user()->hasRole($role->name)) {
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
        if ($this->canEdit($id)) {
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
        if ($this->isTop()) {
            return true;
        }

        if ($this->isEqual($id)) {
            return false;
        } else {
            $contents = Auth::user()->roles()->get();
            if (count($contents) > 0) {
                foreach ($contents as $role) {
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
        $contents = Auth::user()->roles()->get();
        if (count($contents) > 0) {
            foreach ($contents as $role) {
                if (Role::where([['id', $id], ['top_id', $role->top_id], ['active', '1'], ['status', '1']])->first()) {
                    return true;
                }
            }
            return false;
        } else {
            return false;
        }
    }

    public function isTop()
    {
        $contents = Auth::user()->roles()->get();
        if (count($contents) > 0) {
            foreach ($contents as $role) {
                if ($role->top_id == NULL) {
                    return true;
                }
            }
        }
    }

}