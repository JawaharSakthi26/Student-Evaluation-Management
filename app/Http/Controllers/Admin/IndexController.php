<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Title;
use App\Models\User;

class IndexController extends Controller
{
    public function index()
    {
        $totalusers = User::count();
        $totalquiz = Title::count();
        return view('admin.index', compact('totalusers','totalquiz'));
    }
}
