<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyBidRequest;
use App\Http\Resources\API\CertificateResource;
use App\Http\Resources\API\NagotiationResource;
use App\Http\Resources\SuccessResource;
use App\Mail\BidVerifyMail;
use App\Models\Bid;
use App\Models\Certificate;
use App\Models\CounterOffer;
use App\Models\SellCertificate;
use App\Models\Subscription;
use App\Models\User;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class NegotiationController extends Controller
{
    public function viewAllNegotiation(Request $request)
    {
        $per_page = $request->per_page ? $request->per_page : 10;
        try {
            $negotiations = Bid::with('sell_certificate')
                ->WhereHas('sell_certificate',function($query) {
                    $query->where('user_id', auth()->id());
                })
                ->orWhere('user_id', auth()->id())
                ->latest()
                ->paginate($per_page);


            return NagotiationResource::collection($negotiations);
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }

    public function verifyBid(VerifyBidRequest $request)
    {
        try {
            $bid = Bid::find($request->bid_id);
            $status = $request->status;
            if ($status == 1) {
                $certificate = $bid->sell_certificate->certificate;
                $sell_certificate = $bid->sell_certificate;
                $receiver  = $certificate->user;
                $price = $bid->amount;
                $unit = $bid->unit;

                if($request->has('counter_offer_id') && $request->counter_offer_id != '') {
                    $counterOffer = CounterOffer::find($request->counter_offer_id);
                    if ($counterOffer) {
                        $price = $counterOffer->amount;
                        $unit = $counterOffer->quantity;
                    }
                }

                if ($sell_certificate->remaining_units > 0 && $sell_certificate->remaining_units >= $unit) {
                    Subscription::create([
                        'user_id' => $bid->user_id,
                        'receiver_id' => $receiver->id,
                        'name' => $receiver->name,
                        'stripe_id' => $receiver->stripe_id . rand(), //remove
                        'stripe_price' => $price,
                        'amount' => $price,
                        'quantity' => $unit,
                        'stripe_status' => 'success',
                        'certificate_id' => $certificate->id,
                        'sell_certificate_id' => $sell_certificate->id,
                        'card_detail_id' => $bid->card_detail_id,
                        'seller_bank_id' => $receiver->bankAccount->id,
                    ]);
                    $sell_certificate->remaining_units = $sell_certificate->remaining_units - $unit;
                    $sell_certificate->save();
                    $this->createPostCertificates($sell_certificate, $bid->amount, $bid->unit, $bid->user_id);
                }
                $type = "approve";
                $message = "Your bid has been approve successfully";
            } else if ($status == 2) {
                $type = "decline";
                $message = "Your bid has been decline successfully";
            } else if ($status == 3) {
                $existCounterOffer = CounterOffer::where('bid_id', $bid->id)->orderBy('id', 'desc')->first();
                $parentId = ($existCounterOffer) ? $existCounterOffer->parent_id + 1 : 0;

                CounterOffer::create([
                    'bid_id' => $bid->id,
                    'user_id' => auth()->user()->id,
                    'amount' => $request->price,
                    'quantity' => $request->quantity,
                    'type' => (auth()->user()->id == $bid->user->id) ? 'buyer' : 'seller',
                    'parent_id' => $parentId
                ]);

                $type = "offered";
                $message = "Your bid has been offered successfully";
            } else if ($status == 4) {
                $type = "cancelled";
                $message = "Your bid has been cancelled successfully";
            }

            $bid->status = $status;
            $bid->save();
            if($request->has('counter_offer_id') && $request->counter_offer_id != ''){
                CounterOffer::where('id', $request->counter_offer_id)->update([
                    'status' => $status,
                    'status_update_user_id' => auth()->user()->id
                ]);
            }


            $data['title'] = "Your Bid is ".$type;
            $data['body'] = $message;
            Mail::to($bid->user->email)->send(new BidVerifyMail($data));

            $response = ['type'=>$type,'message'=>$message];
            return response()->json($response, 200 );
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }

    }

    public function createPostCertificates($sellCertificate, $amount, $units, $bidUserId)
    {
        $certificate = Certificate::create([
            'user_id' => $bidUserId,
            'project_type_id' => data_get($sellCertificate->certificate, 'project_type_id'),
            'country_id' => data_get($sellCertificate->certificate, 'country_id'),
            'parent_id' => data_get($sellCertificate->certificate, 'id'),
            'name' => data_get($sellCertificate->certificate, 'name'),
            'quantity' => $units,
            'price' => round($amount, 2),
            'description' => data_get($sellCertificate->certificate, 'description'),
            'file_path' => data_get($sellCertificate->certificate, 'file_path'),
            'approving_body' => data_get($sellCertificate->certificate, 'approving_body'),
            'link_to_certificate' => data_get($sellCertificate->certificate, 'link_to_certificate'),
            'status' => 2
        ]);

        SellCertificate::create([
            'certificate_id' => $certificate->id,
            'user_id' => $bidUserId,
            'units' => $units,
            'remaining_units' => $units,
            'price_per_unit' => round($amount/$units,2),
            'is_main' => true,
            'status' => 2,
        ]);
    }

}
