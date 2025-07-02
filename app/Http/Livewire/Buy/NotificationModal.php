<?php

namespace App\Http\Livewire\Buy;

use Livewire\Component;

class NotificationModal extends Component
{
    protected $listeners    =   ['openNotificationModal','closeNotificationModal'];
    public $notifications;
    public $showModal=false;

    public function render()
    {
        $this->notifications=auth()->user()->notifications()->select(\DB::raw('DATE(created_at) as date'))->orderBy('date','desc')->groupBy('date')->get()->toArray();
        foreach ($this->notifications as $key => $notification){
            $this->notifications[$key]['data']=auth()->user()->notifications()->whereDate('created_at',$notification['date'])->orderBy('created_at','desc')->get();
        }

        return view('livewire.buy.notification-modal');
    }

    public function openNotificationModal(){
        if( count($this->notifications)> 0 ){
            $this->showModal = true;
        }
        auth()->user()->unreadNotifications->markAsRead();
    }
    public function closeNotificationModal(){
        $this->showModal = false;
    }
}
