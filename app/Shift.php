<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{

    protected $table = 'shift';
    protected $primaryKey = 'id';

    public static function getAllFromRoleId($id)
    {
        return self::select('id', 'startdate', 'worktime', 'breaktime')->where([['status', '1'], ['roleid', $id]])->get();
    }
    
}