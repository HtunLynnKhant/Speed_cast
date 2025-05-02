<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }
}
