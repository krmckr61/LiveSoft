<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserStatus extends Model
{

    protected $table = 'userstatus';
    protected $primaryKey = 'id';

    public static function setStatus($userId, $status)
    {
        $lastStatus = self::getLastStatus($userId);
        if ($lastStatus != $status) {
            $new = new self();
            $new->userid = $userId;
            $new->status = $status;
            if ($new->save()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function getLastStatus($userId)
    {
        $row = self::where('userid', $userId)->orderBy('id', 'DESC')->first();
        if ($row) {
            return $row->status;
        } else {
            return 1;
        }
    }

    public static function getOfflineCount($userIds, $startDate, $endDate)
    {
        $row = self::select(DB::raw("COUNT(id) as offlinecount"))->where(function ($query) use ($userIds) {
            foreach($userIds as $userId) {
                $query->orWhere('userid', $userId);
            }
        })->where([['status', '!=', '1'], ['created_at', '>=', $startDate], ['created_at', '<', $endDate]])->first();

        if($row) {
            return $row->offlinecount;
        } else {
            return 0;
        }
    }

    public static function getOfflineTime($userIds, $startDate, $endDate)
    {
        $row = self::select(DB::raw("SUM(statustime) as offlinetime"))->where(function ($query) use ($userIds) {
            foreach($userIds as $userId) {
                $query->orWhere('userid', $userId);
            }
        })->where([['status', '!=', '1'], ['created_at', '>=', $startDate], ['created_at', '<', $endDate]])->first();

        if($row) {
            return $row->offlinetime;
        } else {
            return 0;
        }
    }

    public static function getBreakTime($userIds, $startDate, $endDate)
    {
        $row = self::select(DB::raw("SUM(statustime) as breaktime"))->where(function ($query) use ($userIds) {
            foreach($userIds as $userId) {
                $query->orWhere('userid', $userId);
            }
        })->where([['status', '=', '2'], ['created_at', '>=', $startDate], ['created_at', '<', $endDate]])->first();

        if($row) {
            return $row->breaktime;
        } else {
            return 0;
        }
    }

    public static function getBusyTime($userIds, $startDate, $endDate)
    {
        $row = self::select(DB::raw("SUM(statustime) as busytime"))->where(function ($query) use ($userIds) {
            foreach($userIds as $userId) {
                $query->orWhere('userid', $userId);
            }
        })->where([['status', '=', '3'], ['created_at', '>=', $startDate], ['created_at', '<', $endDate]])->first();

        if($row) {
            return $row->busytime;
        } else {
            return 0;
        }
    }

    public static function getOnlineTime($userIds, $startDate, $endDate)
    {
        $row = self::select(DB::raw("SUM(statustime) as onlinetime"))->where(function ($query) use ($userIds) {
            foreach($userIds as $userId) {
                $query->orWhere('userid', $userId);
            }
        })->where([['status', '=', '1'], ['created_at', '>=', $startDate], ['created_at', '<', $endDate]])->first();

        if($row) {
            return $row->onlinetime;
        } else {
            return 0;
        }
    }

}