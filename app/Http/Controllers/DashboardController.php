<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function direktur(){
        return view('dashboard.index');
    }
    public function akuntan(){
        return view('dashboard.index');
    }
    public function admin(){
        return view('dashboard.index');
    }
    public function pengawas(){
        return view('dashboard.index');
    }
}
