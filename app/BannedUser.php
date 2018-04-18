<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BannedUser extends Model
{
    protected $table = 'banneduser';
    protected $primaryKey = 'id';

    public static function get($id)
    {
        return self::select('banneduser.id', 'banneduser.clientid', 'users.name', 'banneduser.date', 'banneduser.created_at', 'banneduser.seen')->join('users', 'banneduser.userid', '=', 'users.id')->where('banneduser.id', $id)->first();
    }

}