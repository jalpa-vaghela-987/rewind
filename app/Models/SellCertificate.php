<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SellCertificate extends Model
{
    use HasFactory;

    protected $guarded = [];

    const STATUS_PENDING = 1;
    const STATUS_APPROVED = 2;
    const STATUS_ON_SELL = 3;
    const STATUS_DECLINED = 4;

    public function certificate(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Certificate::class, 'certificate_id');
    }

    public function subscriptions(): \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this->hasMany(Subscription::class, 'sell_certificate_id', 'id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function bids(): \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this->hasMany(Bid::class, 'sell_certificate_id', 'id');
    }

    public function followers(): \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this->hasMany(CreditFollower::class, 'sell_certificate_id', 'id');
    }
    public function buy_price_alert(): \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this->hasMany(BuyPriceAlert::class, 'sell_certificate_id', 'id');
    }

    public function getPriceDiffPercentageAttribute()
    {
        $percentDiff = 0;
        $lastSoldPrice = Subscription::select(DB::raw('(amount/quantity) as selling_price'))
            ->where('sell_certificate_id', $this->id)
            ->orderBy('id', 'DESC')
            ->value('selling_price');

        if ($lastSoldPrice) {
            $diff = $lastSoldPrice - $this->price_per_unit;
            $sellPercentage = ($diff * 100) / $this->price_per_unit;
            $percentDiff = $sellPercentage;

        }
        return number_format($percentDiff, 2);
    }

    public function getAbleToCancelAttribute()
    {
        if ($this->status == self::STATUS_ON_SELL) {
            return true;
        } else {

        }
        return (bool)$this->status == self::STATUS_ON_SELL;
    }

    public function getTotalPriceAttribute()
    {
        return price_format($this->remaining_units * $this->price_per_unit);
    }

    public function getMainSellCertificate()
    {
        return self::where('certificate_id', $this->certificate_id)->where('is_main', true)->first();
    }

    public static function statuses()
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_APPROVED,
            self::STATUS_ON_SELL,
            self::STATUS_DECLINED
        ];
    }

    public function getChartData($days)
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
        $maxValue = 100;

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
            ->where('sell_certificate_id', $this->id)
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->get();

        if ($certificate->max('price') && $certificate->max('price') > 0) {
            $maxValue = number_format($certificate->max('price'), 2);
        }

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
        return ['data' => $data, 'maxValue' => $maxValue];
    }

    public function priceCalculation($certificate)
    {
        //price calculation
        $amountDifference = 0;

        $today = Carbon::now()->format('Y-m-d');
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        $certificate->price_average = 0;
        $pricePerUnit = $certificate->price_per_unit;
        $todaySubscription = Subscription::where('sell_certificate_id', $certificate->id)
            ->where('certificate_id', $certificate->certificate->id)
            ->where('created_at', 'like', '%' . $today . '%')
            ->get();
        $yesterdaySubscription = Subscription::where('sell_certificate_id', $certificate->id)
            ->where('certificate_id', $certificate->certificate->id)
            ->where('created_at', 'like', '%' . $yesterday . '%')
            ->get();
        if (!empty($todaySubscription) && !empty($yesterdaySubscription)) {
            $todayQuantity = ($todaySubscription->sum('quantity'));
            $yesterdayQuantity = ($yesterdaySubscription->sum('quantity'));
            $todayDifference = ($todayQuantity != 0) ? ($todaySubscription->sum('amount')) / $todayQuantity : 0;
            $yesterdayDifference = ($yesterdayQuantity != 0) ? ($yesterdaySubscription->sum('amount')) / $yesterdayQuantity : 0;
            $amountDifference = $todayDifference - $yesterdayDifference;
            $certificate->price_difference = $amountDifference;
            $price_average = ($amountDifference * 100) / $pricePerUnit;
            $certificate->price_average = number_format($price_average, 2);
        }
        return $certificate;
    }

    public function cancelSellCertificate($sellCertificate){
        $mainSellCertificate = $this->getMainSellCertificate();

        if($sellCertificate->subscriptions->count() > 0 || $sellCertificate->bids->count() > 0 || $sellCertificate->buy_price_alert->count() > 0){
            $mainSellCertificate->remaining_units+=$sellCertificate->remaining_units;
            $mainSellCertificate->save();
            $sellCertificate->remaining_units =0 ;
            $sellCertificate->save();
        }else{
            $mainSellCertificate->remaining_units+=$sellCertificate->remaining_units;
            $mainSellCertificate->save();
            $sellCertificate->delete();
        }
    }
}
