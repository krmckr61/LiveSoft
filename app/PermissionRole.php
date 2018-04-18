<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model
{

    protected $table = 'permission_role';
    public $timestamps = false;


    public static function hasPerm($roleId, $permissionId)
    {
        $perm = self::where([['permission_id', $permissionId], ['role_id', $roleId]])->first();
        if ($perm) {
            return true;
        } else {
            return false;
        }
    }

    public static function attach($roleId, $permissionId)
    {
        $rolePermission = new PermissionRole();
        $rolePermission->role_id = $roleId;
        $rolePermission->permission_id = $permissionId;
        $rolePermission->save();
    }

}