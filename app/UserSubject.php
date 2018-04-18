<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSubject extends Model
{
    protected $table = 'usersubject';
    protected $primaryKey = 'id';

    public static function getSubjectsFromUserId($userId)
    {
        return self::where([['userid', $userId]])->get();
    }

    public static function removeFromUserId($userId)
    {
        return self::where('userid', $userId)->delete();
    }

}