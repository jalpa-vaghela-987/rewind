<?php

namespace App\Http\Livewire\Dashboard\Guest;

use App\Models\Certificate;
use App\Models\Subscription;
use Carbon\Carbon;
use Livewire\Component;

class GuestDashboardLatestBuy extends Component
{
    public $currentValue = null;

    public function render()
    {
        $certificates = Certificate::whereHas('sell_certificate')
            ->with('sell_certificate')
            ->orderBy('id', 'desc')
            ->paginate(15);

        $today = Carbon::now()->format('Y-m-d');
        $yesterday = Carbon::yesterday()->format('Y-m-d');

        foreach ($certificates as $index => $certificate) {
            //find price average
            $certificate->price_average = 0;
            $pricePerUnit = $certificate->sell_certificate->total_amount / $certificate->sell_certificate->units;
            $todaySubscription = Subscription::where('certificate_id', $certificate->id)->where('created_at', 'like', '%' . $today . '%')->first();
            $yesterdaySubscription = Subscription::where('certificate_id', $certificate->id)->where('created_at', 'like', '%' . $yesterday . '%')->first();
            if (!empty($todaySubscription) && !empty($yesterdaySubscription) && $pricePerUnit > 0)
            {
                $amountDifference = ($todaySubscription->amount - $yesterdaySubscription->amount);
                $price_average = ($amountDifference * 100) / $pricePerUnit;
                $certificate->price_average = number_format($price_average, 2);
            }
        }

        return view('livewire.dashboard.guest.guest-dashboard-latest-buy', compact('certificates'));
    }

    public function setCurrentValue()
    {
        return $this->currentValue;
    }
}
