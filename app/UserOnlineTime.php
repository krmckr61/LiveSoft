<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserOnlineTime extends Model
{
    protected $table = 'useronlinetime';
    protected $primaryKey = 'id';

    public static function getTotalOnlineTime($userIds, $startDate, $endDate)
    {
        $row = self::select(DB::raw("SUM(onlinetime) AS totalonlinetime"))->where(function ($query) use ($userIds) {
            foreach($userIds as $userId) {
                $query->orWhere("userid", $userId);
            }
        })->where([['connectiontime', '>=', $startDate], ['connectiontime', '<', $endDate]])->first();

        if($row) {
            return $row->totalonlinetime;
        } else {
            return 0;
        }
    }

}
