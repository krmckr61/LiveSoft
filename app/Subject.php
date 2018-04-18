<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subject';
    protected $primaryKey = 'id';

    public static function get($id)
    {
        return self::where('id', $id)->first();
    }

    public static function getAll()
    {
        return self::where([['status', '1']])->get();
    }
    

//    public static function getSubjectsFromUserId($userId)
//    {
//        return self::where([['userid', $userId]])->get();
//    }

}