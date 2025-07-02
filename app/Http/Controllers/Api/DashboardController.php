<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\BidResource;
use App\Http\Resources\API\SellCertificateResource;
use App\Http\Resources\SubscriptionResource;
use App\Models\Bid;
use App\Models\SellCertificate;
use App\Models\Subscription;
use Exception;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function latestPurchase(Request $request){
        try{
            $certificates   =   Subscription::where('user_id',auth()->user()->id)
                                ->orderBy('id','desc')
                                ->take(5)
                                ->get();

            foreach($certificates as $certificate){
                $sellCertificate = SellCertificate::find($certificate->sell_certificate_id);
                $certificate->price_average = $sellCertificate->priceCalculation($sellCertificate)->price_average;
                $certificate->price_difference = $sellCertificate->priceCalculation($sellCertificate)->price_difference;
            }

            return SubscriptionResource::collection($certificates);
        }catch(Exception $e){
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }
    public function getTrendingCertificates(Request $request){
        try{
            $per_page       =   $request->has('per_page')?$request->per_page:10;
            $certificates   =   SellCertificate::with('certificate')
                                ->withCount('subscriptions')
                                ->whereHas('subscriptions')
                                ->where('user_id','!=',auth()->user()->id)
                                ->where('remaining_units',">",0)
                                ->orderby('subscriptions_count', 'DESC')
                                ->paginate($per_page);

            foreach($certificates as $certificate){
                $certificate->price_average = $certificate->priceCalculation($certificate)->price_average;
                $certificate->price_difference = $certificate->priceCalculation($certificate)->price_difference;
            }

            return SellCertificateResource::collection($certificates);
        }catch(Exception $e){
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }
    public function latestSales(Request $request){
        try{
            $certificates   =   Subscription::where('receiver_id',auth()->user()->id)
                                ->orderBy('id','desc')
                                ->take(5)
                                ->get();
            return SubscriptionResource::collection($certificates);
        }catch(Exception $e){
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }
    Public function bids(Request $request){
        try{
            $per_page       =   $request->has('per_page')?$request->per_page:10;
            $bids           =   Bid::where('user_id',auth()->user()->id)
                                ->orderby('id', 'DESC')
                                ->paginate($per_page);
            foreach($bids as $bid){
                $difference = ($bid->rate * 100) /$bid->sell_certificate->price_per_unit;
                $bid->priceDifference = $difference;
                $bid->differenceType = $bid->rate > $bid->sell_certificate->price ? 'inc' : 'dec';
            }
            return BidResource::collection($bids);
        }catch(Exception $e){
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }
}
