<?php

namespace App\Http\Livewire\Admin\Home;

use App\Models\Subscription;
use Livewire\Component;

class DashboardDeals extends Component
{
    public $deals;
    public $sort_by="subscriptions.created_at";
    public function render(){
        $this->deals    =   Subscription::with('certificate')
                            ->whereHas('certificate')
                            ->select('subscriptions.*')
                            ->join('certificates','subscriptions.certificate_id','certificates.id')
                            ->join('project_types','project_types.id','certificates.project_type_id')
                            ->join('users','subscriptions.user_id','users.id')
                            ->orderBy($this->sort_by,'DESC')
                            ->take(5)
                            ->get();
        return view('livewire.admin.home.dashboard-deals');
    }
}
