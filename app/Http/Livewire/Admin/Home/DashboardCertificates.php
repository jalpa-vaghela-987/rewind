<?php

namespace App\Http\Livewire\Admin\Home;

use App\Models\Certificate;
use Livewire\Component;

class DashboardCertificates extends Component
{
    public $certificates;
    public function render()
    {
        $this->certificates =   Certificate::orderBy('created_at','DESC')->take(3)->get();
        return view('livewire.admin.home.dashboard-certificates');
    }
}
