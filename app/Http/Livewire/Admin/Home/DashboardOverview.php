<?php

namespace App\Http\Livewire\Admin\Home;

use App\Models\Bid;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class DashboardOverview extends Component
{
    public $overViewData    =   array();
    public function render()
    {
        $startDate      = Carbon::createFromTimestamp(strtotime('2023-01-01'));
        $current        = Carbon::now();
        $total_weeks_past  =  $startDate->diffInWeeks($current);
        $this->overViewData['Total Users']      =   $total_users    =   User::role('user')->count();
        $this->overViewData['Users Per Week']   =   $total_users/$total_weeks_past;
        $this->overViewData['Total Deals Done'] =   $total_deals    =   Subscription::count();
        $this->overViewData['Deals Per Week']   =   $total_deals/$total_weeks_past;
        $this->overViewData['Total Index']       =   $total_bids    =   Bid::count();
        $this->overViewData['Index Per Week']    =   $total_bids/$total_weeks_past;
        return view('livewire.admin.home.dashboard-overview');
    }
}
