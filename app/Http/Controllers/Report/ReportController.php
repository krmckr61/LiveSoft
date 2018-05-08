<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Notification;
use App\Role;
use App\RoleUser;
use App\ShiftUser;
use App\User;
use App\UserOnlineTime;
use App\UserStatus;
use App\Visit;
use App\VisitUser;
use Illuminate\Http\Request;

class ReportController extends Controller
{

    private $roleId;

    private $startDate;

    private $endDate;

    private $dayCount;

    public function __construct()
    {
        $this->middleware('permission:shift');
        $this->roleId = Role::getIdFromName('canli-destek');
    }

    public function index()
    {
        return view('Report.index', ['users' => RoleUser::getUsersFromRoleId($this->roleId)]);
    }

    public function getReport(Request $request)
    {
        $users = $request->input('users');
        $dateRange = $request->input('dateRange');

//        $users = [1];
//        $dateRange = '2018-03-17 00:00 - 2018-03-18 00:00';

        $this->initDates($dateRange);

        $data = [];
        $data['totalShiftTime'] = $this->secondsToHours(ShiftUser::getTotalShiftTime($users, $this->startDate, $this->endDate) * 60);
        $data['totalShiftBreakTime'] = $this->secondsToHours(ShiftUser::getTotalBreakTime($users, $this->startDate, $this->endDate) * 60);
        $data['totalWorkTime'] = $this->secondsToHours(UserStatus::getOnlineTime($users, $this->startDate, $this->endDate));
        $data['totalOfflineTime'] =  $this->secondsToHours(UserStatus::getOfflineTime($users, $this->startDate, $this->endDate));
        $data['totalOfflineCount'] = UserStatus::getOfflineCount($users, $this->startDate, $this->endDate);

        $data['totalTakenClientCount'] = Visit::getTakenClientsCount($users, $this->startDate, $this->endDate);
        $data['avgVisitPoint'] = Visit::getAbsVisitPoint($users, $this->startDate, $this->endDate);
        $takenClientSeconds = Visit::getTakenClientsTime($users, $this->startDate, $this->endDate);
        $data['takenClientTime'] = $this->secondsToHours($takenClientSeconds);
        if($data['totalTakenClientCount']) {
            $data['avgClientTime'] = $this->secondsToHours(round($takenClientSeconds / $data['totalTakenClientCount']));
        } else {
            $data['avgClientTime'] = 0;
        }

        $data['faultyMessages'] = Notification::getFaultyMessages($users, $this->startDate, $this->endDate);
        $data['lowScoreVisits'] = Visit::getLowScoreVisits($users, $this->startDate, $this->endDate);
        $data['users'] = User::getUsersFromIds($users);
        $data['daysCount'] = $this->dayCount;
        $data['dateRange'] = $this->startDate . ' - ' . $this->endDate;

//        dd($data);

        return response()->json($data);

    }

    public function initDates($dateRange)
    {
        $this->startDate = explode(' - ', $dateRange)[0];
        $this->endDate = explode(' - ', $dateRange)[1];

        $dateDiff = strtotime($this->endDate) - strtotime($this->startDate);

        $this->dayCount = round($dateDiff / (60 * 60 * 24));
    }

    private function secondsToHours($seconds)
    {
        $string = '';
        if($seconds && $seconds > 0) {
            if ($seconds >= 3600) {
                $string .= ceil($seconds / 3600) . ' saat ';
                $seconds = $seconds % 3600;
            }

            if ($seconds >= 60) {
                $string .= ceil($seconds / 60) . ' dakika ';
                $seconds = $seconds % 60;
            }

            if ($seconds > 0) {
                $string .= $seconds . ' saniye';
            }
        } else {
            $string = '0';
        }

        return $string;

    }

}