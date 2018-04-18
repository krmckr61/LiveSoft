<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'message';
    protected $primaryKey = 'id';

    public static function getMessages($visitId)
    {
        return self::where('visitid', $visitId)->get();
    }

}