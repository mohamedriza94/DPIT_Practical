<?php

namespace App\Http\Controllers\Client\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    private $clientData;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->clientData = Auth::guard('client')->user();
            return $next($request);
        });
    }

    public function dashboard()
    {
        $view_data['title'] = 'DPIT - Client'; $view_data['client'] = $this->clientData->name;
        return view('client.dashboard.index')->with($view_data)
    }
}
