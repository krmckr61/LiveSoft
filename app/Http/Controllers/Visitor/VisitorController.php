<?php

namespace App\Http\Controllers\Visitor;

use App\Http\Controllers\Controller;

class VisitorController extends Controller {

    public function index()
    {
        return view('Visitor.index');
    }

}