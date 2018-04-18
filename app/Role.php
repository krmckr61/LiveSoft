<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{

    protected $table = 'roles';
    protected $primaryKey = 'id';

    protected $subRoles;

    public static function getFromTopId($topId = NULL) {
        return self::where([['status', '1'], ['top_id', $topId]])->get();
    }

    public static function getRoles()
    {
        return self::where([['status', '1']])->get();
    }

    public static function get($id)
    {
        return self::where(function($query) {
            $query->where('status', '1');
            $query->orWhere('status', '0');
        })->where('id', $id)->first();
    }

    public static function getFromDisplayName($name)
    {
        return self::where(function($query) {
            $query->where('status', '1');
        })->where('display_name', $name)->first();
    }

    public static function getIdFromName($name)
    {
        $row = self::where(function($query) {
            $query->where('status', '1');
        })->where('name', $name)->first();
        if($row) {
            return $row->id;
        } else {
            return false;
        }
    }

    public function permissions()
    {
        return $this->belongsToMany('App\Permission', Config::get('entrust::permission_role_table'));
    }

}