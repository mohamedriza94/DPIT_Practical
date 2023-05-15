<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function dashboard()
    {
        $view_data['title'] = 'DPIT - Admin';
        return view('admin.dashboard.index')->with($view_data);
    }

    public function item()
    {
        $view_data['title'] = 'DPIT - Admin';
        return view('admin.dashboard.item')->with($view_data);
    }
}
