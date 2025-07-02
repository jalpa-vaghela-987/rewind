<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BidCertificateRequest;
use App\Http\Requests\BuyCertificateRequest;
use App\Http\Resources\API\BuyIndexCertificateResource;
use App\Http\Resources\API\BuyViewCertificateResource;
use App\Http\Resources\API\BuyViewChartCertificateResource;
use App\Mail\BidApprovalMail;
use App\Models\BankDetail;
use App\Models\Bid;
use App\Models\BuyPriceAlert;
use App\Models\Certificate;
use App\Models\SellCertificate;
use App\Models\Subscription;
use App\Services\StripeHelper;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class BuyCertificateController extends Controller
{
    public function viewAllCertificate(Request $request)
    {
        try {
            $per_page = $request->per_page ? $request->per_page : 10;
            $days = $request->days ?? '7D';
            $userId = Auth::id();

            $sellCertificates = SellCertificate::with('certificate')
                ->where('remaining_units', '>', 0)
                ->where('is_main', 0)
                ->where('user_id', '!=', $userId)
                ->orderBy('id', 'desc')
                ->paginate($per_page);

            foreach ($sellCertificates as $index => $certificate) {
                $certificate->chart = ($this->getChartData($certificate->id,$days)) ?? [];;

                //price calculation
                $certificate->price_average = $certificate->priceCalculation($certificate)->price_average;
                $certificate->price_difference = $certificate->priceCalculation($certificate)->price_difference;
            }
            return BuyIndexCertificateResource::collection($sellCertificates);
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }

    public function getChartData($certificateId, $days)
    {
        if ($days == '1D') {
            $days = 2;
        } elseif ($days == '7D') {
            $days = 7;
        } elseif ($days == '1M') {
            $days = 30;
        } elseif ($days == '6M') {
            $days = 6;
        }


        $data = [];

        if ($days != 6) {
            $startDate = Carbon::now()->subDays($days - 1);
            $endDate = Carbon::now();
            $from = Carbon::now()->subDays($days - 1)->format('Y-m-d');
            $to = Carbon::now()->format('Y-m-d');
        } else {
            $startDate = Carbon::now()->subMonths($days - 1);
            $endDate = Carbon::now();
            $from = Carbon::now()->subMonths($days - 1)->format('Y-m-d');
            $to = Carbon::now()->format('Y-m-d');
        }
        $certificate = Subscription::select('*')
            ->with('certificate')
            ->where('sell_certificate_id', $certificateId)
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->get();

        if ($days != 6) {
            $certificate = $certificate->groupBy('order_date');
        } else {
            $certificate = $certificate->groupBy('order_month');
        }
        $j = 0;
        if ($days != 6) {
            for ($i = $startDate; $i < $endDate; $i->addDay()) {
                $currntDate = $i->format('d/m/y');
                $value = 0;
                if (isset($certificate[$currntDate]) && $certificate[$currntDate]->count() > 0) {
                    $value = number_format($certificate[$currntDate]->avg('price'), 2);
                }
                $data[] = ['x' => $j, 'y' => $value, 'date' => $i->format('d/m/y')];
                $j++;
            }
        } else {
            for ($i = $startDate; $i < $endDate; $i->addMonth()) {
                $currntDate = $i->format('M');
                $value = 0;
                if (isset($certificate[$currntDate]) && $certificate[$currntDate]->count() > 0) {
                    $value = number_format($certificate[$currntDate]->avg('price'), 2);
                }
                $data[] = ['x' => $j, 'y' => $value, 'date' => $i->format('M')];
                $j++;
            }
        }
        return $data;
    }

    public function viewCertificate(Request $request)
    {
        try {
            $userId = Auth::id();
            $sellCertificateId = $request->id;

            $sellCertificates = SellCertificate::where('id', $sellCertificateId)
                ->where('remaining_units', '>', 0)
                ->where('is_main', 0)
                ->where('user_id', '!=', $userId)
                ->count();

            if ($sellCertificates <= 0) {
                $response = [
                    'message' => 'Login user certificate not found',
                    'data' => null
                ];
                return response()->json($response, 200);
            } else {
                $sellCertificate = SellCertificate::where('id', $sellCertificateId)->first();
                $sellCertificate->price_average = $sellCertificate->priceCalculation($sellCertificate)->price_average;
                return BuyViewCertificateResource::make($sellCertificate);
            }
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }

    public function viewCertificateChart(Request $request)
    {
        try {
            $userId = Auth::id();
            $sellCertificateId = $request->id;

            $sellCertificates = SellCertificate::where('id', $sellCertificateId)
                ->where('remaining_units', '>', 0)
                ->where('is_main', 0)
                ->where('user_id', '!=', $userId)
                ->count();

            if ($sellCertificates <= 0) {
                $response = [
                    'message' => 'Login user certificate not found',
                    'data' => null
                ];
                return response()->json($response, 200);
            } else {
                $days = $request->days ?? 14;
                $dataLabel = $this->getLabels($days);
                $dataSet = $this->getRandomData($days, $sellCertificateId);
                $maxValue = $dataSet['maxValue'];
                $stepSize = $dataSet['stepSize'];

                unset($dataSet['maxValue']);
                unset($dataSet['stepSize']);

                return BuyViewChartCertificateResource::make([
                    'data' =>  array_values($dataSet),
                    'labels' => $dataLabel,
                    'maxValue' => round($maxValue),
                    'stepSize' => $stepSize
                ]);
            }
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }

    }

    private function getLabels($days)
    {
        $labels = [];
        if($days == 14 || $days == 30) {
            $startDate = Carbon::now()->subDays($days - 1);
            $endDate = Carbon::now();
            for ($i = $startDate; $i < $endDate; $i->addDay()) {
                $labels[] = $i->format('D');
            }
        }else if($days == 180){
            $counter = 6;
            $startDate = Carbon::now()->subMonths($counter - 1);
            $endDate = Carbon::now();
            for ($i = $startDate; $i < $endDate; $i->addMonth()) {
                $labels[] = $i->format('M');
            }
        }else if($days == 365){
            $counter = 12;
            $startDate = Carbon::now()->subMonths($counter - 1);
            $endDate = Carbon::now();
            for ($i = $startDate; $i < $endDate; $i->addMonth()) {
                $labels[] = $i->format('M');
            }
        }
        return $labels;
    }

    public function getRandomData($days, $sellCertificateId)
    {
        $data = [];
        $maxValue = 0;
        $stepSize = 0;

        if($days == 14 || $days == 30) {
            $j = 0;
            $startDate = Carbon::now()->subDays($days - 1);
            $endDate = Carbon::now();

            $from = Carbon::now()->subDays($days - 1)->format('Y-m-d');
            $to = Carbon::now()->format('Y-m-d');

            $certificate = Subscription::select('*')->with('certificate')->where('sell_certificate_id',$sellCertificateId)
                ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
                ->get();

            $certificate = $certificate->groupBy('order_date');

            for ($i = $startDate; $i < $endDate; $i->addDay()) {
                $currntDate = $i->format('d/m/y');
                $value = 0;
                if(isset($certificate[$currntDate]) && $certificate[$currntDate]->count() > 0){
                    $value = $certificate[$currntDate]->sum('amount');
                }
                $data[] = ['x'=>$j,'y'=>$value,'date'=> $i->format('d/m/y')];
                $j++;
            }
        } else if ($days == 180){
            $counter = 6;
            $startDate = Carbon::now()->subMonths($counter - 1);
            $endDate = Carbon::now();
            $from = Carbon::now()->subMonths($counter - 1)->format('Y-m-d');
            $to = Carbon::now()->format('Y-m-d');
            $certificate = Subscription::select('*')->with('certificate')->where('sell_certificate_id',$sellCertificateId)
                ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
                ->get();
            $certificate = $certificate->groupBy('order_month');

            $j = 0;
            for ($i = $startDate; $i < $endDate; $i->addMonth()) {
                $currntDate = $i->format('M');
                $value = 0;
                if(isset($certificate[$currntDate]) && $certificate[$currntDate]->count() > 0){
                    $value = $certificate[$currntDate]->sum('price');
                }
                $data[] = ['x'=>$j,'y'=>$value,'date'=> $i->format('M')];
                $j++;
            }
        } else if ($days == 365){
            $counter = 12;
            $startDate = Carbon::now()->subMonths($counter - 1);
            $endDate = Carbon::now();

            $from = Carbon::now()->subMonths($counter - 1)->format('Y-m-d');
            $to = Carbon::now()->format('Y-m-d');
            $certificate = Subscription::select('*')->with('certificate')->where('sell_certificate_id',$sellCertificateId)
                ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
                ->get();
            $certificate = $certificate->groupBy('order_month');

            $j = 0;
            for ($i = $startDate; $i < $endDate; $i->addMonth()) {
                $currntDate = $i->format('M');
                $value = 0;
                if(isset($certificate[$currntDate]) && $certificate[$currntDate]->count() > 0){
                    $value = $certificate[$currntDate]->sum('price');
                }
                $data[] = ['x'=>$j,'y'=>$value,'date'=> $i->format('M')];
                $j++;
            }
        }

        if (!empty($data))
        {
            foreach ($data as $chart)
            {
                if($chart['y'] > $maxValue)
                {
                    $maxValue = $chart['y'];
                }
            }
            $maxValue = $maxValue * 2;
            $stepSize = round($maxValue / 10);
        }

        $data['maxValue'] = $maxValue;
        $data['stepSize'] = $stepSize;
        return $data;
    }

    public function buy(BuyCertificateRequest $request){
        try{
            $sellCertificateId = $request->sell_certificate_id;
            $sellCertificate = SellCertificate::find($sellCertificateId);

            $buyer          =   $request->user();
            $cardDetail     =   $buyer->creditCard;
            $receiver       =   $sellCertificate->certificate->user;
            $amount         =   $sellCertificate->price_per_unit * $request->units;

            // $response       =   (new StripeHelper($buyer))->useCard($seller, $amount, $cardDetail);
            $subscription   =   Subscription::create([
                'user_id' => $request->user()->id,
                'receiver_id' => $receiver->id,
                'name' =>$receiver->name,
                'stripe_id' => $receiver->stripe_id . rand(), //remove
                'stripe_price' => $amount,
                'amount' => $amount,
                'quantity' => $request->units,
                'stripe_status' => 'success',
                'certificate_id' => $sellCertificate->certificate->id,
                'sell_certificate_id' => $sellCertificate->id,
                'card_detail_id' => $cardDetail->id,
                'seller_bank_id' => BankDetail::where('user_id',$receiver->id)->where('is_primary',true)->first()->id
            ]);

            if ($sellCertificate->remaining_units > 0) {
                SellCertificate::where('id', $sellCertificate->id)->update([
                    'remaining_units' => $sellCertificate->remaining_units - $request->units
                ]);
            }

            $this->createPostCertificates($sellCertificate, $request->units, $amount);
            $message = 'Unit: <b>:subject.quantity</b> of <b>:subject.certificate.name</b> certificate purchased';
            activity()
            ->performedOn($subscription)
            ->causedBy($request->user())
            ->log($message);

            return response()->json([
                'message' => 'Your payment has been success',
                'data' => null
            ], 200);
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }

    public function createPostCertificates($sellCertificate, $unit, $amount)
    {
        $certificate = Certificate::create([
            'user_id' => auth()->id(),
            'project_type_id' => data_get($sellCertificate->certificate, 'project_type_id'),
            'country_id' => data_get($sellCertificate->certificate, 'country_id'),
            'parent_id' => data_get($sellCertificate->certificate, 'id'),
            'name' => data_get($sellCertificate->certificate, 'name'),
            'quantity' => $unit,
            'price' => round($amount, 2),
            'description' => data_get($sellCertificate->certificate, 'description'),
            'file_path' => data_get($sellCertificate->certificate, 'file_path'),
            'approving_body' => data_get($sellCertificate->certificate, 'approving_body'),
            'link_to_certificate' => data_get($sellCertificate->certificate, 'link_to_certificate'),
            'status' => 2
        ]);

        SellCertificate::create([
            'certificate_id' => $certificate->id,
            'user_id' => auth()->id(),
            'units' => $unit,
            'remaining_units' => $unit,
            'price_per_unit' => round($amount/$unit,2),
            'is_main' => true,
            'status' => 2,
        ]);
    }

    public function bidCertificate(BidCertificateRequest $request)
    {
        try {
            $sender = auth()->user();
            $sellCertificate = SellCertificate::find($request->sell_certificate_id);
            $receiver = $sellCertificate->certificate->user;
            $cardDetailId = $sender->creditCard->id;

            $expirationDate = Carbon::createFromFormat('d/m/Y', $request->expiration_date)->format('Y-m-d');
            $bid = Bid::create([
                'certificate_id' => $sellCertificate->certificate->id,
                'sell_certificate_id' => $sellCertificate->id,
                'user_id' => $sender->id,
                'amount' => $request->rate * $request->units,
                'rate' => $request->rate,
                'unit' => $request->units,
                'initial_quantity' => $sellCertificate->remaining_units,
                'expiration_date' => $expirationDate,
                'card_detail_id' => $cardDetailId
            ]);

            $details['url'] = route('offers');
            $details['title'] = "New bid is added in your negotiation list by ". $sender->name;
            $details['body'] = 'Please go to this link and reply for negotiation';
            Mail::to($receiver->email)->send(new BidApprovalMail($details));

            $message = 'A quantity of '.$bid->unit.' <b>:subject.certificate.project_type.type</b> type certificates has been Bidding';
            activity()
                ->performedOn($bid)
                ->causedBy(auth()->user())
                ->log($message);

            $response = [
                'message' => 'Your payment has been success',
                'data' => null
            ];
            return response()->json($response, 200);
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }

    public function buyPriceAlert(Request $request){
        try {
            $this->validate($request, [
                'sell_certificate_id' => ['required', 'exists:sell_certificates,id'],
                'alert_percentage' => ['required', 'numeric'],
            ], [
                'sell_certificate_id.required' => "Certificate Id required.",
                'sell_certificate_id.exists' => "Invalid Certificate.",
                'alert_percentage.required' => 'Quantity required.',
                'alert_percentage.numeric' => 'Quantity should be numeric.',
            ]);

            $sellCertificate = SellCertificate::find($request->sell_certificate_id);
            if($request->alert_percentage != 0){
                $percentage= ($request->alert_percentage * $sellCertificate->price_per_unit) /100 ;
                $alert_price = $percentage + $sellCertificate->price_per_unit;
            }else{
                $alert_price = $sellCertificate->price_per_unit;
            }

            $sellAlert = BuyPriceAlert::updateOrCreate(
                [
                    'sell_certificate_id' => $sellCertificate->id,
                    'user_id' => auth()->id()
                ],
                [
                    'sell_certificate_id' => $sellCertificate->id,
                    'certificate_id' => $sellCertificate->certificate->id,
                    'user_id' => auth()->id(),
                    'amount' => $alert_price,
                    'percentage' => $request->alert_percentage
                ]);
            return response()->json(['message' => 'Price Alert Set successfully', 'data' => null], 200);
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }
}
