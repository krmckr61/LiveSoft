<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notification';
    protected $primaryKey = 'id';

    public static function getFaultyMessages($userIds, $startDate, $endDate)
    {
        return self::select('notification.id', 'users.name', 'notification.text', 'notification.created_at')->join('users', 'notification.userid', '=', 'users.id')->where(function ($query) use ($userIds) {
            foreach ($userIds as $userId) {
                $query->orWhere('notification.userid', $userId);
            }
        })->where([['notification.created_at', '>=', $startDate], ['notification.created_at', '<', $endDate]])->get();
    }

}