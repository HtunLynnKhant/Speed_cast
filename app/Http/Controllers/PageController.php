<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class PageController extends Controller
{
    /**
     * Dashboard view.
     *
     * @return View
     */
    public function dashboard(): View
    {
        return view('dashboard');
    }
}
