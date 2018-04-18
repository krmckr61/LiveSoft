<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Visit;
use App\User;
use Illuminate\Support\Facades\DB;

class VisitUser extends Model
{
    protected $table = 'visituser';
    protected $primaryKey = 'id';

    public static function getUsers($visitId)
    {
        $visitUsers = self::select('userid')->where('visitid', $visitId)->get();
        if (count($visitUsers) > 0) {
            $users = User::select('id', 'name')->where(function ($query) use ($visitUsers) {
                foreach ($visitUsers as $visitUser) {
                    $query->orWhere('id', $visitUser->userid);
                }
            })->get();
            if(count($users) > 0) {
                $return = [];
                foreach($users as $user) {
                    $return[$user->id] = $user->name;
                }
                return $return;
            }
        } else {
            return false;
        }
    }

}