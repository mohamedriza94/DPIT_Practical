<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\Request AS PurchaseRequest;
use App\Models\Item;
use App\Models\Client;
use PDF;

class PurchaseRequestController extends Controller
{
    public function read()
    {
        $data = PurchaseRequest::join('items','requests.item','=','items.code')
        ->join('clients','requests.client','=','clients.id')
        ->orderBy('requests.id','DESC')->get([
            'requests.id AS id',
            'requests.no AS no',
            'items.name AS itemName',
            'items.code AS itemCode',
            'clients.email AS clientEmail',
            'requests.status AS status',
            'requests.quantity AS quantity',
            'requests.itemCost AS cost',
            'requests.updated_at AS createdAt'
        ]);
        return response()->json(['data' => $data]);
    }
    
    public function readOne($id)
    {
        $data = PurchaseRequest::join('items','requests.item','=','items.code')
        ->join('clients','requests.client','=','clients.id')
        ->where('requests.id','=',$id)
        ->orderBy('requests.id','DESC')->first([
            'items.name AS itemName',
            'items.code AS itemCode',
            'clients.email AS clientEmail',
            'requests.status AS status',
            'requests.quantity AS quantity',
            'requests.itemCost AS cost',
            'requests.updated_at AS createdAt'
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
                'message' => 'required|string'
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

                //get item quantity
                $PurchaseRequestData = PurchaseRequest::find($request->input('id'));
                $ItemData = Item::where('code',$PurchaseRequestData->item)->first();

                //get associated client's data
                $client = $PurchaseRequestData->client;
                $client = Client::find($client);

                $newQuantity = '0';

                //check if stock is enough
                if($status == '1' && ($ItemData->quantity < $PurchaseRequestData->quantity))
                {
                    //if stock is NOT enough
                    DB::rollBack();
                    return response()->json([
                        'status'=>400,
                        'message'=>'Insufficient stocks available to approve'
                    ]);
                }
                else
                {
                    //if stock is enough
                    $newQuantity = $ItemData->quantity - $PurchaseRequestData->quantity;
                }
                
                switch ($status) {
                    case '1': 
                        $newStatus = 'approved';

                        //update item quantity
                        $ItemData->quantity = $newQuantity;
                        $ItemData->save();
                        
                        //CREATE AND MAIL INVOICE
                        //get data for pdf
                        $note = 'Your Request No. '.$PurchaseRequestData->no.' has been approved.';
                        $data["email"] = $client->email;
                        $data["title"] = 'Request Approval';
                        $data["requestNo"] = $PurchaseRequestData->no;
                        $data["approvalDate"] = date("Y-m-d");
                        $data["approvalTime"] = date("h:i A");
                        $data["itemCode"] = $ItemData->code;
                        $data["itemName"] = $ItemData->name;
                        $data["unitPrice"] = $PurchaseRequestData->itemCost;
                        $data["quantity"] = $PurchaseRequestData->quantity;
                        $data["total"] = $PurchaseRequestData->itemCost * $PurchaseRequestData->quantity;
                        $data["note"] = $note;

                        $pdf = PDF::loadView('mail.invoicePDF',$data);
                        
                        Mail::send('mail.invoiceEmail', $data, function($message)use($data, $pdf, $note) {
                            $message->to($data["email"], $data["email"])
                            ->subject($data["title"])
                            ->attachData($pdf->output(), "Invoice.pdf");
                        });
                        
                    break;
                    
                    case '0': 
                        $newStatus = 'disapproved'; 
                        
                        //send mail
                        $data["email"] = $client->email;
                        $data["title"] = "Request Disapproval";
                        $data["requestNo"] = $PurchaseRequestData->no;
                        $data["reason"] = $request->input('message');
                        
                        Mail::send('mail.disapprovalMail', $data, function($message)use($data) {
                            $message->to($data["email"])
                            ->subject($data["title"]);
                        }); 

                    break;
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
            'requests.id AS id',
            'requests.no AS no',
            'items.name AS itemName',
            'items.code AS itemCode',
            'clients.email AS clientEmail',
            'requests.status AS status',
            'requests.quantity AS quantity',
            'requests.itemCost AS cost',
            'requests.updated_at AS createdAt'
        ]);
        return response()->json(['data' => $data]);
    }
}
