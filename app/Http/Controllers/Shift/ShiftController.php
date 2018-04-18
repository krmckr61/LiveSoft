<?php

namespace App\Http\Controllers\Shift;


use App\Config;
use App\Http\Controllers\Controller;
use App\Http\Libraries\DataSeeker\DataSeeker;
use App\Role;
use App\RoleUser;
use App\Shift;
use App\ShiftUser;
use Illuminate\Http\Request;

class ShiftController extends Controller
{

    private $url = '/shifts';

    public function __construct()
    {
        $this->middleware('permission:shift');
    }

    public function index()
    {
        $role = Role::getIdFromName('canli-destek');
        return redirect(url('shifts/edit/' . $role));
//        return view('Shift.index');
    }

    public function edit($id, Request $request)
    {
        $role = Role::get($id);
        if ($role) {
            $users = RoleUser::getUsersFromRoleId($id);
            $shifts = Shift::getAllFromRoleId($id);
            $arr = [];
            if (count($shifts) > 0) {
                foreach ($shifts as $shift) {
                    $arr[] = $this->getShiftToEvent($shift);
                }
            }

            $workTime = Config::getValue('workTime');
            $breakTime = Config::getValue('breakTime');

            return view('Shift.edit', ['users' => $users, 'role' => $role, 'shifts' => $arr, 'workTime' => $workTime, 'breakTime' => $breakTime]);
        } else {
            $request->session()->flash('alert', ['type' => 'warning', 'message' => 'Böyle bir içerik bulunamadı.']);
            return redirect(url($this->url));
        }
    }

    public function getDatas()
    {
        $ds = new DataSeeker();
        $ds->multipleEvent = false;
        $ds->pk = 'id';
        $ds->table = 'roles';
        $ds->fields = [
            'display_name' => ['type' => 'string'],
        ];
        $ds->wheres[] = ['status', '1'];
        $ds->wheres[] = ['active', '1'];
        $ds->transactions = [
            ['href' => $this->url . '/edit/{#id#}', 'color' => 'info', 'text' => 'Düzenle', 'icon' => 'fa-pencil-square-o'],
        ];
        return $ds->getDatas($_POST);
    }

    public function addShift(Request $request)
    {
        $shift = new Shift();
        $shift->status = '0';
        if ($shift->save()) {
            $return = ['type' => 'success', 'shift' => Shift::find($shift->id)];
        } else {
            $return = ['type' => 'warning'];
        }
        return response()->json($return);
    }

    public function getShift($id)
    {
        $shift = Shift::find($id);
        if ($shift) {
            $shiftUsers = ShiftUser::getUsersFromShiftId($id);
            $users = [];
            if (count($shiftUsers) > 0) {
                foreach ($shiftUsers as $shiftUser) {
                    $users[] = $shiftUser->userid;
                }
            }
            $shift->worktime /= 60;
            $return = ['type' => 'success', 'shift' => $shift, 'users' => $users];
        } else {
            $return = ['type' => 'warning'];
        }
        return response()->json($return);
    }

    public function saveShift($id, Request $request)
    {
        $shift = Shift::find($id);
        if ($shift) {
            $startDate = $request->input('startdate');
            $workTime = $request->input('worktime');
            $breakTime = $request->input('breaktime');
            $roleId = $request->input('roleid');
            $users = $request->input('userIds');


            if ($startDate && $workTime && $breakTime && $workTime < 24 && count($users) > 0) {

                $workTime *= 60;

                foreach($users as $user) {
                    if(ShiftUser::hasShiftFromUser($user, $startDate, $workTime)) {
                        return ['type' => 'warning', 'message' => 'Bazı kullanıcıların bu tarih/saatlere ait mevcut mesaileri var.'];
                    }
                }

                ShiftUser::removeUsersFromShiftId($id);
                foreach ($users as $user) {
                    $shiftUser = new ShiftUser();
                    $shiftUser->userid = $user;
                    $shiftUser->shiftid = $id;
                    $shiftUser->save();
                }
                $shift->roleid = $roleId;
                $shift->startdate = date('Y-m-d H:i', strtotime($startDate));
                $shift->worktime = $workTime;
                $shift->breaktime = $breakTime;
                $shift->status = '1';
                if ($shift->save()) {
                    $return = ['type' => 'success', 'shift' => $this->getShiftToEvent($shift)];
                } else {
                    $return = ['type' => 'warning', 'message' => 'Ekleme/Düzenleme işlemi gerçekleştirilirken hata meydana geldi.'];
                }
            } else {
                $return = ['type' => 'warning', 'message' => 'Lütfen gereken alanların tamamını doldurunuz.'];
            }
        } else {
            $return = ['type' => 'error'];
        }
        return response()->json($return);
    }

    public function getShiftToEvent($shift)
    {
        if (is_numeric($shift)) {
            $shift = Shift::get($shift);
            $users = ShiftUser::getUserNamesFromShiftId($shift);
        } else {
            $users = ShiftUser::getUserNamesFromShiftId($shift->id);
        }

        if ($shift) {
            $arr = [
                'id' => $shift->id,
                'start' => date('Y/m/d H:i', strtotime($shift->startdate)),
                'end' => date('Y/m/d H:i', strtotime($shift->startdate) + ($shift->worktime * 60))
            ];
            if($users) {
                $arr['title'] = date('H:i', strtotime($shift->startdate)) . " - " . date('H:i', strtotime($shift->startdate) + ($shift->worktime * 60)) . "\n\r" . implode("\n\r", $users);
            } else {
                $arr['title'] = date('H:i', strtotime($shift->startdate)) . date('H:i', strtotime($shift->startdate) + ($shift->worktime * 60));
            }
            return $arr;
        } else {
            return false;
        }
    }

    public function deleteShift(Request $request)
    {
        $id = $request->input('id');
        $shift = Shift::find($id);
        if($shift) {
            $shift->status = '2';
            if($shift->save()) {
                $return = ['type' => 'success', 'id' => $id];
            } else {
                $return = ['type' => 'error'];
            }
        } else {
            $return = ['type' => 'error'];
        }

        return response()->json($return);
    }

    public function getOffUsers($roleId, Request $request)
    {
        $date = $request->input('date');
        $users = RoleUser::getUsersFromRoleId($roleId);
        $offUsers = ShiftUser::getOffUsers($date, $users);
        return response()->json(['type' => 'success', 'offUsers' => $offUsers]);
    }

}