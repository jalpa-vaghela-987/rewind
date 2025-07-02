<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionDetailRequest;
use App\Http\Resources\API\TransactionResource;
use App\Http\Resources\API\SubscriptionResource;
use App\Models\Subscription;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function myTransactionsList(Request $request){
        try{
            $per_page           =   $request->per_page?$request->per_page:10;
            $transactions       =   Subscription::where(function($query) use($request){
                                            $query->where('user_id',$request->user()->id)
                                            ->orWhere('receiver_id',$request->user()->id);
                                        })->where('stripe_status','success')
                                        ->paginate($per_page);
            return SubscriptionResource::collection($transactions);
        }catch(Exception $e){
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }
    public function MyTransactionDetail(TransactionDetailRequest $request){
        try{
            $transaction       =   Subscription::where(function($query) use($request){
                                            $query->where('user_id',$request->user()->id)
                                            ->orWhere('receiver_id',$request->user()->id);
                                        })->where('id',$request->transaction_id)
                                        ->where('stripe_status','success')
                                        ->first();
            if($transaction){
                return SubscriptionResource::make($transaction);
            }else{
                throw new Exception("Transaction Data not found");
            }
        }catch(Exception $e){
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }
}
