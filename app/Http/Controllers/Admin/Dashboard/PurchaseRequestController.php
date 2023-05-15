<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Request AS PurchaseRequest;
use App\Models\Item;

class PurchaseRequestController extends Controller
{
    public function read()
    {
        $data = PurchaseRequest::join('items','requests.item','=','items.code')
        ->join('clients','requests.client','=','clients.id')
        ->orderBy('requests.id','DESC')->get([
            'items.name AS itemName',
            'items.code AS itemCode',
            'clients.email AS clientEmail',
            'requests.status AS status',
            'requests.quantity AS quantity',
            'requests.itemCost AS cost',
            'requests.created_at AS createdAt'
        ]);
        return response()->json(['data' => $data]);
    }
    
    public function readOne($id)
    {
        $data = PurchaseRequest::join('items','requests.item','=','items.code')
        ->join('clients','requests.client','=','clients.id')
        ->where('requests.id','=',$id)
        ->orderBy('requests.id','DESC')->get([
            'items.name AS itemName',
            'items.code AS itemCode',
            'requests.status AS status',
            'requests.quantity AS quantity',
            'requests.itemCost AS cost',
            'requests.created_at AS createdAt'
        ]);
        return response()->json(['data' => $data]);
    }

    public function updateStatus(Request $request)
    {
        DB::beginTransaction();
        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [          
                'id' => 'required|numeric|exists:requests,id',
            ]);
            
            if ($validator->fails()) { 
                return response()->json([
                    'status'=>800,
                    'errors'=>$validator->messages()
                ]);
            }
            else
            {
                //get input status
                $status = $request->input('status');
                $newStatus = '-';

                switch ($status) {
                    case '1': $newStatus = 'approved'; break;
                    
                    case '0': $newStatus = 'disapproved'; break;
                }

                PurchaseRequest::where('id',$request->input('id'))->update([ 
                    'status' => $newStatus]);

                DB::commit();
            }
            
        } catch (\Exception $e) {
            
            DB::rollBack();
            return response()->json([
                'status'=>400,
                'message'=>'Could not update status. Try again'
            ]);
        }
        
        return response()->json([
            'status'=>200,
            'message'=>'Request Status Updated Successfully'
        ]);
    }
    
    public function search($search)
    {
        $data = PurchaseRequest::join('items','requests.item','=','items.code')
        ->join('clients','requests.client','=','clients.id')
        ->where('requests.no','Like','%'.$search.'%')
        ->orderBy('requests.id','DESC')->get([
            'items.name AS itemName',
            'items.code AS itemCode',
            'clients.email AS clientEmail',
            'requests.status AS status',
            'requests.quantity AS quantity',
            'requests.itemCost AS cost',
            'requests.created_at AS createdAt'
        ]);
        return response()->json(['data' => $data]);
    }
}
