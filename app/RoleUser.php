<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{

    protected $table = 'role_user';
    protected $primaryKey = 'id';

    public static function getUserRoles($userId)
    {
        return self::where('user_id', $userId)->get();
    }

    public static function removeAllRoles($userId)
    {
        return self::where('user_id', $userId)->delete();
    }

    public static function getUsersFromRoleId($roleId)
    {
        $rows = self::select('user_id')->where('role_id', $roleId)->get();
        if(count($rows) > 0) {
            $userIds = [];
            foreach($rows as $row) {
                $userIds[] = $row->user_id;
            }
            return User::getUsersFromIds($userIds);
        } else {
            return [];
        }
    }

}