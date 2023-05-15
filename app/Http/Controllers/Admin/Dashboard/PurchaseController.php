<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Purchase;

class PurchaseController extends Controller
{
    public function read()
    {
        $data = Purchase::join('items','purchases.item','=','items.code')
        ->join('clients','purchases.client','=','clients.id')
        ->orderBy('purchases.id','DESC')->get([
            'items.name AS itemName',
            'items.code AS itemCode',
            'purchases.request AS request',
            'clients.email AS clientEmail',
            'purchases.status AS status',
            'purchases.quantity AS quantity',
            'purchases.itemCost AS cost',
            'purchases.created_at AS createdAt'
        ]);
        return response()->json(['data' => $data]);
    }
}

