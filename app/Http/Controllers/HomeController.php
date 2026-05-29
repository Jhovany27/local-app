<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (! Auth::check()) {
            return redirect('/admin/login');
        }

        return redirect()->route('portal');
    }

    public function portal()
    {
        return view('portal');
    }
}