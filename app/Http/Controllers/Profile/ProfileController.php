<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    public function index()
    {
        $userRoles = Auth::user()->roles()->get();
        return view('Profile.index', ['user' => Auth::user(), 'userRoles' => $userRoles]);
    }

    public function update(Request $request)
    {
        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $password2 = $request->input('password2');

        if ($name && $email) {
            $user = Auth::user();
            $user->name = $name;
            $user->email = $email;
            if ($password) {
                if ($password == $password2) {
                    $user->password = Hash::make($password);
                } else {
                    $request->session()->flash('alert', ['type' => 'warning', 'message' => 'Parola alanları aynı değerleri içermelidir.']);
                    return redirect(url('/profile'));
                }
            }
            if ($user->save()) {
                $request->session()->flash('alert', ['type' => 'success', 'message' => 'Kaydetme işlemi başarıyla gerçekleşti.']);
            } else {
                $request->session()->flash('alert', ['type' => 'warning', 'message' => 'Kaydetme işlemi gerçekleştirilirken hata meydana geldi.']);
            }
        } else {
            $request->session()->flash('alert', ['type' => 'warning', 'message' => 'Lütfen gereken alanları eksiksiz doldurunuz.']);
        }

        return redirect(url('/profile'));
    }

    public function setOnlineStatus(Request $request)
    {
        $status = $request->input('onlinestatus');
        if(User::setOnlineStatus(Auth::user()->id, $status)) {
            return response()->json(['type' => 'success']);
        } else {
            return response()->json(['type' => 'error']);
        }
    }

}