<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Item;

class ItemController extends Controller
{
    public function create(Request $request)
    {
        DB::beginTransaction();
        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [     
                'quantity' => 'required|numeric',
                'name' => 'required|string|max:255',
                'unitCost' => 'required|numeric',
            ]);
            
            if ($validator->fails()) { 
                return response()->json([
                    'status'=>800,
                    'errors'=>$validator->messages()
                ]);
            }
            else
            {
                //generate an item code
                $cost = rand(0000,9999);
                $exists = Item::where('code', $code)->exists();
                while ($exists) {
                    $code = rand(0000,9999);
                    $exists = Item::where('code', $code)->exists();
                }

                //check availablity of stock
                $isAvailable = '0';
                $quantity = $request->input('quantity');
                if($quantity >= 1)
                {
                    $isAvailable = '1';
                }
                else
                {
                    $isAvailable = '0';
                }
                
                Item::create([ 
                    'code' => $code,
                    'quantity' => $request->input('quantity'),
                    'name' => $request->input('name'),
                    'isAvailable' => $isAvailable,
                    'unitCost' => $request->input('unitCost'),
                ]);
                
                DB::commit();
            }
            
        } catch (\Exception $e) {
            
            DB::rollBack();
            return response()->json([
                'status'=>400,
                'message'=>'Could not create Item. Try again'
            ]);
        }
        
        return response()->json([
            'status'=>200,
            'message'=>'Item Created Successfully'
        ]);
    }
    
    public function read()
    {
        $data = Item::orderBy('id','DESC')->get();
        return response()->json(['data' => $data]);
    }
    
    public function readOne($id)
    {
        $data = Item::find($id);
        return response()->json(['data' => $data]);
    }
    
    public function search($search)
    {
        $data = Item::where('code','Like','%'.$search.'%')->orderBy('id','DESC')->get();
        return response()->json(['data' => $data]);
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        try {
            // Validate the request data
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:items,id',     
                'quantity' => 'required|numeric',
                'name' => 'required|string|max:255',
                'unitCost' => 'required|numeric',
            ]);
            
            if ($validator->fails()) { 
                return response()->json([
                    'status'=>800,
                    'errors'=>$validator->messages()
                ]);
            }
            else
            {
                //check availablity of stock
                $isAvailable = '0';

                //calculate quantity
                $itemData = Item::find($request->input('id'));
                $quantity = $itemData->quantity + $request->input('quantity');

                if($quantity >= 1)
                {
                    $isAvailable = '1';
                }
                else
                {
                    $isAvailable = '0';
                }
                
                Item::where('id',$request->input('id'))->update([ 
                    'quantity' => $quantity,
                    'name' => $request->input('name'),
                    'isAvailable' => $isAvailable,
                    'unitCost' => $request->input('unitCost'),
                ]);
                
                DB::commit();
            }
            
        } catch (\Exception $e) {
            
            DB::rollBack();
            return response()->json([
                'status'=>400,
                'message'=>'Could not update Item. Try again'
            ]);
        }
        
        return response()->json([
            'status'=>200,
            'message'=>'Item Updated Successfully'
        ]);
    }
    
    public function updateQuantity($id)
    {
        $itemData = Item::find($id);
        $itemData->quantity = $itemData->quantity + 1;
        $itemData->save();
        
        return response()->json([
            'status'=>200,
        ]);
    }
}
