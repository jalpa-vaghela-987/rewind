<?php

namespace App\Http\Livewire\Profile\Activity;

use App\Models\ActivityLog;
use Carbon\Carbon;
use Livewire\Component;

class MyActivityLog extends Component
{
    public $activity_duration = 'day';
    public function render()
    {
        $data = ActivityLog::where('causer_id', auth()->user()->id)
            ->when($this->activity_duration == 'year', function ($query) {
                $query->whereYear('created_at', date('Y'));
            })
            ->when($this->activity_duration == 'month', function ($query) {
                $query->whereBetween('created_at', [Carbon::now()->startOfMonth()->format('Y-m-d'), Carbon::now()->endOfMonth()->format('Y-m-d')]);
            })
            ->when($this->activity_duration == 'six_month', function ($query) {

                $query->whereBetween('created_at', [Carbon::now()->subMonth(6), Carbon::now()]);
            })
            ->when($this->activity_duration == 'week', function ($query) {
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek()->format('Y-m-d'), Carbon::now()->endOfWeek()->format('Y-m-d')]);
            })
            ->when($this->activity_duration == 'day', function ($query) {
                $query->whereDate('created_at', Carbon::now()->format('Y-m-d'));
            })->orderBy('created_at','desc')->get()->groupBy(function ($item) {
                return $item->created_at->format('D d, M');
            });
        $activities= $data;
        $activity_duration_dropdown = ['day'=>'1 Day','week'=>'1 Week','month'=>'1 Month','six_month'=>'6 Month'];
        return view('livewire.profile.activity.my-activity-log',compact('activities','activity_duration_dropdown'));
    }
    public function changeActivityDuration($activity_duration){
        $this->activity_duration = $activity_duration;
        $this->render();
    }
}
