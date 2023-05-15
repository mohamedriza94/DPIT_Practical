<?php

namespace App\Http\Controllers\Client\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Request AS PurchaseRequest;
use App\Models\Item;

class PurchaseRequestController extends Controller
{
    private $clientData;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->clientData = Auth::guard('client')->user();
            return $next($request);
        });
    }

    public function readItems()
    {
        $data = Item::where('isAvailable',1)->orderby('id','DESC')->get();
        return response()->json(['data' => $data]);
    }

    public function create(Request $request)
    {
        DB::beginTransaction();
        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [     
                'item' => 'required|string',
                'quantity' => 'required|numeric',
            ]);
            
            if ($validator->fails()) { 
                return response()->json([
                    'status'=>800,
                    'errors'=>$validator->messages()
                ]);
            }
            else
            {
                //generate a request no
                $no = rand(0000,9999);
                $exists = PurchaseRequest::where('no', $no)->exists();
                while ($exists) {
                    $no = rand(0000,9999);
                    $exists = PurchaseRequest::where('no', $no)->exists();
                }

                //get item details
                $itemData = Item::find($request->input('item'));

                PurchaseRequest::create([ 
                    'no' => $no,
                    'client' => $clientData->id,
                    'status' => 'pending',
                    'item' => $request->input('item'),
                    'quantity' => $request->input('quantity'),
                    'itemCost' => $itemData->unitCost,]);

                DB::commit();
            }
            
        } catch (\Exception $e) {
            
            DB::rollBack();
            return response()->json([
                'status'=>400,
                'message'=>'Could not make request. Try again'
            ]);
        }
        
        return response()->json([
            'status'=>200,
            'message'=>'Request Made Successfully'
        ]);
    }
    
    public function read()
    {
        $data = PurchaseRequest::join('items','requests.item','=','items.code')
        ->join('clients','requests.client','=','clients.id')
        ->where('requests.client','=',$clientData->id)
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
    
    public function readOne($id)
    {
        $data = PurchaseRequest::join('items','requests.item','=','items.code')
        ->join('clients','requests.client','=','clients.id')
        ->where('requests.client','=',$clientData->id)->where('requests.id','=',$id)
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

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [          
                'id' => 'required|numeric|exists:requests,id',
                'item' => 'required|string',
                'quantity' => 'required|numeric',
            ]);
            
            if ($validator->fails()) { 
                return response()->json([
                    'status'=>800,
                    'errors'=>$validator->messages()
                ]);
            }
            else
            {
                //get item details
                $itemData = Item::find($request->input('item'));

                PurchaseRequest::where('id',$request->input('id'))->update([ 
                    'status' => 'pending',
                    'item' => $request->input('item'),
                    'quantity' => $request->input('quantity'),
                    'itemCost' => $itemData->unitCost,]);

                DB::commit();
            }
            
        } catch (\Exception $e) {
            
            DB::rollBack();
            return response()->json([
                'status'=>400,
                'message'=>'Could not update request. Try again'
            ]);
        }
        
        return response()->json([
            'status'=>200,
            'message'=>'Request Updated Successfully'
        ]);
    }
    
    public function search($search)
    {
        $data = PurchaseRequest::join('items','requests.item','=','items.code')
        ->join('clients','requests.client','=','clients.id')
        ->where('requests.client','=',$clientData->id)->where('requests.no','Like','%'.$search.'%')
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
}
