<?php

namespace App;

use function foo\func;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ShiftUser extends Model
{

    protected $table = 'shiftuser';
    protected $primaryKey = 'id';

    public static function getUsersFromShiftId($id)
    {
        return self::select('userid')->where('shiftid', $id)->get();
    }

    public static function removeUsersFromShiftId($id)
    {
        self::where('shiftid', $id)->delete();
    }

    public static function getUserNamesFromShiftId($id)
    {
        $users = self::getUsersFromShiftId($id);
        if (count($users) > 0) {
            $rows = User::select('name')->where(function ($query) use ($users) {
                foreach ($users as $user) {
                    $query->orWhere('id', $user->userid);
                }
            })->where([['status', '1'], ['active', '1']])->get();
            if (count($rows) > 0) {
                $users = [];
                foreach ($rows as $row) {
                    $users[] = $row->name;
                }
                return $users;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function getOffUsers($date, $users)
    {
        $workUsers = self::select('shiftuser.userid')->join('shift', 'shiftuser.shiftid', '=', 'shift.id')->where(function ($query) use ($users) {
            if (count($users) > 0) {
                foreach ($users as $user) {
                    $query->orWhere('shiftuser.userid', $user->id);
                }
            }
        })->where([['shift.startdate', $date], ['shift.status', '1']])->get();

        if (count($workUsers) > 0) {
            foreach ($workUsers as $workUser) {
                foreach ($users as $key => $user) {
                    if ($workUser->userid == $user->id) {
                        unset($users[$key]);
                    }
                }
            }
        }
        return $users;
    }

    public static function getTotalShiftTime($userIds, $startDate, $endDate)
    {
        $row = self::select(DB::raw("SUM(shift.worktime) - SUM(shift.breaktime) AS worktime"))->join('shift', 'shiftuser.shiftid', '=', 'shift.id')->where(function ($query) use ($userIds) {
            foreach ($userIds as $userId) {
                $query->orWhere("shiftuser.userid", $userId);
            }
        })->where([['startdate', '>=', $startDate], ['startdate', '<', $endDate]])->first();

        if ($row) {
            return $row->worktime;
        } else {
            return 0;
        }
    }

    public static function getTotalBreakTime($userIds, $startDate, $endDate)
    {
        $row = self::select(DB::raw("SUM(shift.breaktime) AS breaktime"))->join('shift', 'shiftuser.shiftid', '=', 'shift.id')->where(function ($query) use ($userIds) {
            foreach ($userIds as $userId) {
                $query->orWhere("shiftuser.userid", $userId);
            }
        })->where([['startdate', '>=', $startDate], ['startdate', '<', $endDate]])->first();

        if ($row) {
            return $row->breaktime;
        } else {
            return 0;
        }
    }

    public static function hasShiftFromUser($userId, $startDate, $workTime)
    {
        $endDate = date('Y-m-d H:i', strtotime($startDate) + $workTime * 60);

        $row = self::select('shiftuser.id')->join('shift', 'shiftuser.shiftid', '=', 'shift.id')->where([['shiftuser.userid', $userId], ['shift.status', 1]])->whereRaw(
            "
                (
                    (shift.startdate + (shift.worktime * interval '1 minute') >= '" . $startDate . "' AND shift.startdate <= '" . $startDate . "') OR 
                    (shift.startdate + (shift.worktime * interval '1 minute') >= '" . $endDate . "' AND shift.startdate <= '" . $endDate . "') OR
                    (shift.startdate + (shift.worktime * interval '1 minute') <= '" . $endDate . "' AND shift.startdate >= '" . $startDate .  "')
                )
            ")->get();

        if(count($row) > 0) {
            return true;
        } else {
            return false;
        }

    }

}