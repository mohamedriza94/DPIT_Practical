<?php

namespace App\Http\Controllers\Client\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Purchase;

class PurchaseController extends Controller
{
    private $clientData;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->clientData = Auth::guard('client')->user();
            return $next($request);
        });
    }
    
    public function read()
    {
        $data = Purchase::join('items','purchases.item','=','items.code')
        ->join('clients','purchases.client','=','clients.id')
        ->where('purchases.client','=',$clientData->id)
        ->orderBy('purchases.id','DESC')->get([
            'items.name AS itemName',
            'items.code AS itemCode',
            'purchases.request AS request',
            'purchases.status AS status',
            'purchases.quantity AS quantity',
            'purchases.itemCost AS cost',
            'purchases.created_at AS createdAt'
        ]);
        return response()->json(['data' => $data]);
    }
}
