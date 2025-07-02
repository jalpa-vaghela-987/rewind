<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddCertificateRequest;
use App\Http\Requests\ReadNotificationRequest;
use App\Http\Resources\API\CertificateResource;
use App\Http\Resources\API\SellCertificateResource;
use App\Http\Resources\SuccessResource;
use App\Models\BuyPriceAlert;
use App\Models\Certificate;
use App\Models\SellCertificate;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\SendMessageNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SellCertificateController extends Controller
{
    public $maxDays = 14;
    public $maxValue = 100;
    public $stepSize = 10;
    public function saveSellCertificate(AddCertificateRequest $request)
    {
        try {
            $saved = Certificate::create([
                'project_type_id' => $request->project_type_id,
                'name' => $request->name,
                'country_id' => $request->country_id,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'approving_body' => $request->approving_body,
                'link_to_certificate' => $request->link_to_certificate,
                'user_id' => auth()->id(),
                'status' => 1
            ]);

            SellCertificate::create([
                'certificate_id' => $saved->id,
                'user_id' => auth()->id(),
                'units' => $request->quantity,
                'remaining_units' => $request->quantity,
                'price_per_unit' => round($request->price/$request->quantity,2),
                'is_main' => true,
                'status' => 1,
            ]);

            $admin = User::role(ROLE_ADMIN)->first();

            $approve_url = route('validateCertificate', ['certificate_id' => $saved->id, 'status' => 'Approve']);
            $decline_url = route('validateCertificate', ['certificate_id' => $saved->id, 'status' => 'Decline']);
            $details['title'] = "Please verify your email";
            $details['approve_url'] = $approve_url;
            $details['decline_url'] = $decline_url;
            $details['id'] = $saved->id;
            $details['body'] = 'Hello,New Certificate has been added, Please check the details and approve or decline it.';

            Mail::to($admin->email)->send(new \App\Mail\CertificateVerifyMail($details));
            return SuccessResource::make(['message' => 'Certificate Added Successfully.']);

        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }

    }

    public function viewCertificate(Request $request)
    {
        $this->validate($request, [
            'sell_certificate_id' => ['required', 'exists:sell_certificates,id'],
        ], [
            'sell_certificate_id.required' => "Sell Certificate Id required.",
            'sell_certificate_id.exists' => "Invalid Sell Certificate.",

        ]);
        try {
            $sell_certificate =  SellCertificate::where(['id' => $request->sell_certificate_id, 'user_id' => auth()->id()])->first();
            if(!$sell_certificate){
                $response = [
                    'message' => 'InValid Certificate Access',
                    'data' => null,
                ];
                return response()->json($response, 404);
            }
            $response = [
                'message' =>"Certificate Display successfully",
                'data' => SellCertificateResource::make($sell_certificate),
            ];
            return response()->json($response, 200);

        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data' => null,
            ];
            return response()->json($response, 500);
        }
    }

    public function viewAllCertificate(Request $request)
    {
        try {
            $per_page = $request->per_page ? $request->per_page : 10;
            $sellCertificates = SellCertificate::with('certificate')
                ->where('user_id', auth()->id())
                ->whereHas("certificate",function($q){
                    $q->where("deleted_at", null);
                })
                ->where('remaining_units', '>', 0)
                ->orderBy('id', 'desc')->paginate($per_page);

            foreach ($sellCertificates as $certificate) {
                //price calculation
                $certificate->price_average = $certificate->priceCalculation($certificate)->price_average;
                $certificate->price_difference = $certificate->priceCalculation($certificate)->price_difference;
            }
            return SellCertificateResource::collection($sellCertificates);
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }

    public function sellCertificate(Request $request)
    {
        $this->validate($request, [
            'sell_certificate_id' => ['required', 'exists:sell_certificates,id'],
            'unit' => ['required', 'numeric', 'gt:0'],
            'price_per_unit' => ['required', 'numeric', 'gt:0'],
        ], [
            'sell_certificate_id.required' => "Sell Certificate Id required.",
            'sell_certificate_id.exists' => "Invalid Sell Certificate.",
            'unit.required' => 'Quantity required.',
            'unit.numeric' => 'Quantity should be numeric.',
            'price_per_unit.required' => 'Price Per Unit required.',
            'price_per_unit.numeric' => 'Price Per Unit should be numeric.',

        ]);
        try {
            $certificate = SellCertificate::findorFail($request->sell_certificate_id);

            $sell_certificate = new SellCertificate();
            $sell_certificate->certificate_id = $certificate->certificate_id;
            $sell_certificate->user_id = auth()->id();
            $sell_certificate->units =$request->unit;
            $sell_certificate->remaining_units =$request->unit;
            $sell_certificate->price_per_unit = $request->price_per_unit;
            $sell_certificate->status = 3;
            $sell_certificate->save();

            $main_sell_certificate = $certificate->getMainSellCertificate();
            $main_sell_certificate->remaining_units = abs($main_sell_certificate->remaining_units - $request->unit);
            $main_sell_certificate->save();

            return response()->json(['message' => 'Sell Certificate successfully added for sell', 'data' => SellCertificateResource::make($sell_certificate)], 200);
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }
    public function updateUnitPrice(Request $request)
    {
        $this->validate($request, [
            'sell_certificate_id' => ['required', 'exists:sell_certificates,id'],
            'unit_price' => ['required', 'numeric', 'gt:0'],
        ], [
            'sell_certificate_id.required' => "Sell Certificate Id required.",
            'sell_certificate_id.exists' => "Invalid Sell Certificate.",
            'unit_price.required' => 'Unit Price required.',
            'unit_price.numeric' => 'Unit Price should be numeric.',
        ]);
        try {
            $sell_certificate = SellCertificate::findOrfail($request->sell_certificate_id);
            if($sell_certificate->is_main && $sell_certificate->status==SellCertificate::STATUS_PENDING){
                $sell_certificate->certificate->price = $sell_certificate->certificate->quantity * $request->unit_price;
                $sell_certificate->certificate->save();
                $sell_certificate->price_per_unit = $request->unit_price;
                $sell_certificate->save();
            }else{
                $existed_sell_certificate = SellCertificate::where('price_per_unit', $request->unit_price)
                    ->where('status', SellCertificate::STATUS_ON_SELL)
                    ->where('certificate_id', $sell_certificate->certificate_id)
                    ->whereNot('id', $sell_certificate->id)
                    ->first();

                if($existed_sell_certificate){
                    if($sell_certificate->subscriptions->count() > 0 || $sell_certificate->bids->count() > 0){
                        //Important Note: Don't change the sequence of below 6 lines
                        $existed_sell_certificate->remaining_units += $sell_certificate->remaining_units;
                        $existed_sell_certificate->units += $sell_certificate->remaining_units;
                        $sell_certificate->units -= $sell_certificate->remaining_units;
                        $sell_certificate->remaining_units = 0;
                        $existed_sell_certificate->save();
                        $sell_certificate->save();

                    }else{
                        //Important Note: Don't change the sequence of below 4 lines
                        $existed_sell_certificate->remaining_units += $sell_certificate->remaining_units;
                        $existed_sell_certificate->units += $sell_certificate->remaining_units;
                        $existed_sell_certificate->save();
                        $this->setBuyPriceAlert($existed_sell_certificate);
                        $sell_certificate->delete();
                        return response()->json(['message' => 'Unit Price Updated Successfully', 'data' => SellCertificateResource::make($existed_sell_certificate)], 200);

                    }
                }else{
                    $sell_certificate->price_per_unit = $request->unit_price;
                    $sell_certificate->save();
                }
            }
            $this->setBuyPriceAlert($sell_certificate);
            return response()->json(['message' => 'Unit Price Updated Successfully', 'data' => SellCertificateResource::make($sell_certificate)], 200);
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }
    public function setBuyPriceAlert($sell_certificate)
    {
        if ($sell_certificate && $sell_certificate->remaining_units > 0) {
            $buy_price_alerts = BuyPriceAlert::where([
                'certificate_id' => $sell_certificate->certificate->id,
                'amount' => $sell_certificate->price_per_unit
            ])->get();
            if ($buy_price_alerts) {
                foreach ($buy_price_alerts as $buy_price_alert) {
                    if ($buy_price_alert->percentage >= 0) {
                        $message = "Certificate " . $sell_certificate->certificate->name . " raised by " . $buy_price_alert->percentage . "%";
                    } else {
                        $message = "Certificate " . $sell_certificate->certificate->name . " fallen by " . $buy_price_alert->percentage . "%";
                    }
                    $buy_price_alert->user->notify(new SendMessageNotification($message,'buy'));
                }
            }
        }
    }
    public function updateQuantity(Request $request)
    {
        $this->validate($request, [
            'sell_certificate_id' => ['required', 'exists:sell_certificates,id'],
            'quantity' => ['required', 'numeric', 'gt:0'],
        ], [
            'sell_certificate_id.required' => "Sell Certificate Id required.",
            'sell_certificate_id.exists' => "Invalid Sell Certificate.",
            'quantity.required' => 'Quantity required.',
            'quantity.numeric' => 'Quantity should be numeric.',

        ]);
        try {
            $sell_certificate = SellCertificate::findorFail($request->sell_certificate_id);
            if($sell_certificate->is_main && $sell_certificate->status == 1){
                $sell_certificate->certificate->quantity = $request->quantity;
                $sell_certificate->certificate->save();
                $sell_certificate->units = $request->quantity;
                $sell_certificate->remaining_units = $request->quantity;
                $sell_certificate->save();
            }else{

                //if yes then we can't decrease the quantity less than conducted quantity in that entries
                if($sell_certificate->remaining_units > $request->quantity){
                    $quantity_difference = $sell_certificate->remaining_units - $request->quantity;
                    // decrease the quantity from child sell certificate
                    $sell_certificate->units -= $quantity_difference;
                    // decrease the remaining_quantity from child sell certificate
                    $sell_certificate->remaining_units = $request->quantity;
                    $sell_certificate->save();
                    // increase the remaining_units in main sell certificate
                    $main_certificate = $sell_certificate->getMainSellCertificate();
                    $main_certificate->remaining_units += $quantity_difference;
                    $main_certificate->save();


                }else{
                    $main_certificate = $sell_certificate->getMainSellCertificate();
                    if ($main_certificate->units >= $request->quantity) {
                        $quantity_difference = $request->quantity - $sell_certificate->remaining_units;
                        // increase the quantity in child sell certificate
                        $sell_certificate->units += $quantity_difference;
                        // increase the remaining_quantity in child sell certificate
                        $sell_certificate->remaining_units = $request->quantity;
                        $sell_certificate->save();
                        // decrease the remaining_units from main sell certificate
                        $main_certificate->remaining_units -= $quantity_difference;
                        $main_certificate->save();
                    }else{
                        return response()->json(['message' => 'Quantity must be less than '. $main_certificate->units  , 'data' => SellCertificateResource::make($sell_certificate)], 200);
                    }
                }
            }
            $sell_certificate->certificate->quantity = $request->quantity;
            $sell_certificate->certificate->save();
            return response()->json(['message' => 'Quantity Updated Successfully', 'data' => SellCertificateResource::make($sell_certificate)], 200);
        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }

    public function cancelSellCertificate(Request $request)
    {
        $this->validate($request, [
            'sell_certificate_id' => ['required', 'exists:sell_certificates,id'],
        ], [
            'sell_certificate_id.required' => "Sell Certificate Id required.",
        ]);
        try {
            $sell_certificate = SellCertificate::findorFail($request->sell_certificate_id);
            $main_sell_certificate = $sell_certificate->getMainSellCertificate();
            if($sell_certificate->subscriptions->count() > 0 || $sell_certificate->bids->count() > 0 || $sell_certificate->buy_price_alert->count() > 0){
                $main_sell_certificate->remaining_units+=$sell_certificate->remaining_units;
                $main_sell_certificate->save();
                $sell_certificate->remaining_units =0 ;
                $sell_certificate->save();
            }else{
                $main_sell_certificate->remaining_units+=$sell_certificate->remaining_units;
                $main_sell_certificate->save();
                $sell_certificate->delete();
            }
            $response = [
                'message' => 'Certificate Cancelled successfully!',
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

    public function getChart(Request $request){
        $this->validate($request, [
            'sell_certificate_id' => ['required', 'exists:sell_certificates,id'],
            'days' => ['required'],
        ], [
            'sell_certificate_id.required' => "Certificate Id required.",
            'sell_certificate_id.exists' => "Invalid Certificate.",
            'days.required' => "Days is required.",

        ]);
        try {
            $sell_certificate = SellCertificate::findorFail( $request->sell_certificate_id);
            $data=[];
            if ($request->days == 14 || $request->days == 30) {
                $j = 0;
                $startDate = Carbon::now()->subDays($request->days - 1);
                $endDate = Carbon::now();

                $from = Carbon::now()->subDays($request->days - 1)->format('Y-m-d');
                $to = Carbon::now()->format('Y-m-d');
                $certificate = Subscription::select('*')->with('certificate')
                    ->where('sell_certificate_id', $sell_certificate->id)
                    ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                    ->get();
                if ($certificate->max('price') && $certificate->max('price') > 0) {
                    $this->maxValue = $certificate->max('price');
                    $this->stepSize = round($this->maxValue / 10);

                }
                $certificate = $certificate->groupBy('order_date');


                for ($i = $startDate; $i < $endDate; $i->addDay()) {
                    $currntDate = $i->format('d/m/y');
                    $value = 0;
                    if (isset($certificate[$currntDate]) && $certificate[$currntDate]->count() > 0) {
                        $value = $certificate[$currntDate]->avg('price');
                    }
                    $data[] = ['x' => $j, 'y' => $value, 'date' => $i->format('d/m/y')];
                    $j++;
                }
            } else if ($request->days == 180) {
                $counter = 6;
                $startDate = Carbon::now()->subMonths($counter - 1);
                $endDate = Carbon::now();
                $from = Carbon::now()->subMonths($counter - 1)->format('Y-m-d');
                $to = Carbon::now()->format('Y-m-d');
                $certificate = Subscription::select('*')->with('certificate')
                    ->where('sell_certificate_id', $sell_certificate->id)
                    ->where('receiver_id', auth()->id())
                    ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                    ->get();
                if ($certificate->max('price') && $certificate->max('price') > 0) {
                    $this->maxValue = $certificate->max('price');
                    $this->stepSize = round($this->maxValue / 10);
                }
                $certificate = $certificate->groupBy('order_month');
                $j = 0;
                for ($i = $startDate; $i < $endDate; $i->addMonth()) {
                    $currntDate = $i->format('M');
                    $value = 0;
                    if (isset($certificate[$currntDate]) && $certificate[$currntDate]->count() > 0) {
                        $value = $certificate[$currntDate]->avg('price');
                    }
                    $data[] = ['x' => $j, 'y' => $value, 'date' => $i->format('M')];
                    $j++;
                }
            } else if ($request->days == 365) {
                $counter = 12;
                $startDate = Carbon::now()->subMonths($counter - 1);
                $endDate = Carbon::now();

                $from = Carbon::now()->subMonths($counter - 1)->format('Y-m-d');
                $to = Carbon::now()->format('Y-m-d');
                $certificate = Subscription::select('*')->with('certificate')
                    ->where('sell_certificate_id', $sell_certificate->id)->where('receiver_id', auth()->id())
                    ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                    ->get();
                if ($certificate->max('price') && $certificate->max('price') > 0) {
                    $this->maxValue = $certificate->max('price');
                    $this->stepSize = round($this->maxValue / 10);
                }
                $certificate = $certificate->groupBy('order_month');

                $j = 0;
                for ($i = $startDate; $i < $endDate; $i->addMonth()) {
                    $currntDate = $i->format('M');
                    $value = 0;
                    if (isset($certificate[$currntDate]) && $certificate[$currntDate]->count() > 0) {
                        $value = $certificate[$currntDate]->avg('price');
                    }
                    $data[] = ['x' => $j, 'y' => $value, 'date' => $i->format('M')];
                    $j++;
                }
            }
            return response()->json(['message' => 'Certificate Chart is successfully displayed', 'data' => $data], 200);

        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }

    public function getNotification(Request $request){
        try {
            $notifications = auth()->user()->notifications()->select(\DB::raw('DATE(created_at) as date'))->orderBy('date','desc')->groupBy('date')->get()->toArray();
            foreach ($notifications as $key => $notification) {
                if (Carbon::parse($notifications[$key]['date'])->isToday()){
                    $notifications[$key]['date'] = "Today";
                }elseif (Carbon::parse($notifications[$key]['date'])->isYesterday()){
                    $notifications[$key]['date'] = "Yesterday";
                }else{
                    $notifications[$key]['date'] = $notifications[$key]['date'];
                }
                $notifications[$key]['data'] = auth()->user()->notifications()->whereDate('created_at',$notification['date'])->orderBy('created_at','desc')->get();
            }

            return response()->json(['message' => 'Notification Listed successfully', 'data' => $notifications], 200);

        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }
    public function readNotification(ReadNotificationRequest $request){
        try {
            $notifications =  auth()->user()->unreadNotifications->markAsRead();
            if($notifications){
                return response()->json(['message' => 'Notification updated successfully'], 200);
            }else{
                throw new Exception("Error Processing Request", 1);
            }

        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }

    public function getUnReadNotification(Request $request){
        try {
            $notifications = auth()->user()->notifications()->whereNull(["read_at"])->select(\DB::raw('DATE(created_at) as date'))->orderBy('date', 'desc')->groupBy('date')->get()->toArray();
            foreach ($notifications as $key => $notification) {

                if (Carbon::parse($notifications[$key]['date'])->isToday()){
                    $notifications[$key]['date'] = "Today";
                }elseif (Carbon::parse($notifications[$key]['date'])->isYesterday()){
                    $notifications[$key]['date'] = "Yesterday";
                }else{
                    $notifications[$key]['date'] = $notifications[$key]['date'];
                }
                $notifications[$key]['data'] = auth()->user()->notifications()->whereNull(["read_at"])->whereDate('created_at', $notification['date'])->get(['id','message','is_read']);
            }

            return response()->json(['message' => 'Notification Listed successfully', 'data' => $notifications], 200);

        } catch (Exception $e) {
            $response = [
                'message' => $e->getMessage(),
                'data' => null
            ];
            return response()->json($response, 500);
        }
    }
}
