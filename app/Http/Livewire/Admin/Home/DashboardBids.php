<?php

namespace App\Http\Livewire\Admin\Home;

use App\Models\Bid;
use Livewire\Component;

class DashboardBids extends Component
{
    public $bids;
    public $sort_by="bids.created_at";
    public function render()
    {
        $this->bids = Bid::select('bids.*')->with('certificate', 'certificate.project_type')
            ->whereHas('certificate')
            ->join('certificates','bids.certificate_id','certificates.id')
            ->join('project_types','project_types.id','certificates.project_type_id')
            ->orderBy($this->sort_by,'DESC')
            ->take(5)
            ->get();

        return view('livewire.admin.home.dashboard-bids');
    }
}
