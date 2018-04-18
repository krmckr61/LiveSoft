<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PreparedContent\PreparedContentController;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index()
    {

        if(Auth::user()->can('liveSupport')) {
            $preparedContents = PreparedContentController::getContentsToJson();
            return view('Dashboard.index', ['breadcrumb' => false, 'preparedContents' => $preparedContents]);
        } else {
            return redirect(url('/roles'));
        }

    }

}