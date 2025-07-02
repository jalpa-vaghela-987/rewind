<?php

namespace App\Http\Livewire\Admin\Home;

use App\Models\User;
use Livewire\Component;

class DashboardActiveUserList extends Component
{
    public $active_users;
    public $sort_by="users.created_at";
    public function render()
    {
        $this->active_users =   User::select('users.*')
                                ->join('countries','users.country_id','countries.id')
                                ->role('user')
                                ->where('status',1)
                                ->orderBy($this->sort_by,'DESC')
                                ->take(3)
                                ->get();
        return view('livewire.admin.home.dashboard-active-user-list');
    }
}
