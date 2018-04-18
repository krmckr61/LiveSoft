<?php

namespace App;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{

    protected $table = 'permissions';
    protected $primaryKey = 'id';

    public static function getAll()
    {
        return self::where([['status', '1'], ['active', '1']])->get();
    }

    public static function getFromName($name)
    {
        return self::where([['name', $name], ['active', '1'], ['status', '1']])->first();
    }

    public static function getAllWithoutRole()
    {
        return self::where([['status', '1'], ['active', '1'], ['name', '!=', 'role']])->get();
    }

}