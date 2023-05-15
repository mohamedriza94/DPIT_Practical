<?php

namespace App\Http\Controllers\Client\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function dashboard()
    {
        $view_data['title'] = 'DPIT - Client'; 
        return view('client.dashboard.index')->with($view_data);
    }
}
