<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Visit extends Model
{
    protected $table = 'visit';
    protected $primaryKey = 'id';

    public static function get($visitId)
    {
        return self::where([['status', '1'], ['active', '!=', '1'], ['id', $visitId]])->first();
    }

    public static function getTakenClientsCount($userIds, $startDate, $endDate)
    {
        $row = self::select(DB::raw('COUNT(visit.id) AS clientcount'))->join('visituser', 'visit.id', '=', 'visituser.visitid')->where(function ($query) use ($userIds) {
            foreach($userIds as $userId) {
                $query->orWhere('visituser.userid', $userId);
            }
        })->where([['visit.created_at', '>=', $startDate], ['visit.created_at', '<', $endDate]])->first();

        if($row) {
            return $row->clientcount;
        } else {
            return 0;
        }
    }

    public static function getTakenClientsTime($userIds, $startDate, $endDate)
    {
        $row = self::select(DB::raw('SUM(EXTRACT(EPOCH FROM (visit.closed_at - visit.created_at))) AS visittime'))->join('visituser', 'visit.id', '=', 'visituser.visitid')->where(function ($query) use ($userIds) {
            foreach($userIds as $userId) {
                $query->orWhere('visituser.userid', $userId);
            }
        })->where([['visit.created_at', '>=', $startDate], ['visit.created_at', '<', $endDate]])->first();

        if($row) {
            return $row->visittime;
        } else {
            return 0;
        }
    }

    public static function getAbsVisitPoint($userIds, $startDate, $endDate)
    {
        $row = self::select(DB::raw('CAST(AVG(visit.point) as float) as point'))->join('visituser', 'visit.id', '=', 'visituser.visitid')->where(function ($query) use ($userIds) {
            foreach($userIds as $userId) {
                $query->orWhere('visituser.userid', $userId);
            }
        })->where([['visit.created_at', '>=', $startDate], ['visit.created_at', '<', $endDate]])->first();

        if($row) {
            return $row->point;
        } else {
            return 0;
        }
    }

    public static function getBannedClientData($clientId, $banDate)
    {
        return self::select('data')->where([['visitorid', $clientId], ['active', '!=', '1'], ['created_at', '<', $banDate]])->orderBy('id', 'DESC')->first();
    }

    public static function getLowScoreVisits($users, $startDate, $endDate)
    {
        return self::select(DB::raw("visit.id, visit.point, (SELECT STRING_AGG(DISTINCT(users.name), ', ') as username FROM users INNER JOIN visituser ON users.id=visituser.userid WHERE visituser.visitid=visit.id)"))->join('visituser', 'visit.id', '=', 'visituser.visitid')->where(function ($query) use ($users) {
            foreach($users as $user) {
                $query->orWhere('visituser.userid', $user);
            }
        })->where('visit.point', '!=', NULL)->where([['visit.point', '<', '3'], ['visit.created_at', '>=', $startDate], ['visit.created_at', '<', $endDate]])->get();
    }
    
}