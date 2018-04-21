<?php

namespace App;

//use Illuminate\Notifications\Notifiable;
use function foo\func;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use App\UserStatus;

class User extends Authenticatable
{
//    use Notifiable;
    use EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function getName($id)
    {
        $row = self::select('name')->find($id);
        if($row) {
            return $row->name;
        } else {
            return false;
        }
    }

    public static function get($id) {
        return self::where(function ($query) {
            $query->where('status', '1')
                ->orWhere('status', '0');
        })->where('id', $id)->first();
    }

    public function roles()
    {
        return $this->belongsToMany('App\Role', Config::get('entrust::assigned_roles_table'));
    }

    public static function clearAllRoles($userId)
    {
        return self::where('user_id', $u);
    }

    public static function setOnlineStatus($userId, $status)
    {
        $user = self::where('id', $userId)->first();
        if($user) {
            if($user->onlinestatus != $status) {
                if(UserStatus::setStatus($userId, $status)) {
                    $user->onlinestatus = $status;
                    if($user->save()) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function getUsersFromIds($userIds)
    {
        return self::select('id', 'name')->where(function ($query) use ($userIds) {
            foreach($userIds as $userId) {
                $query->orWhere('id', $userId);
            }
        })->where([['active', '1'], ['status', '1']])->get();
    }

}
