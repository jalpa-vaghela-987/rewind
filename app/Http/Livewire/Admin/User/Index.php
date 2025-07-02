<?php

namespace App\Http\Livewire\Admin\User;

use App\Http\Livewire\Table\Lists;
use App\Models\Company;
use App\Models\Subscription;
use App\Models\User;
use App\Mail\ApproveUserMail;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class Index extends Lists
{

    public $search = '';
    public $sortField ='id';
    public $sortAsc = false;

    public function render()
    {
        $users = User::with(['country'])
            ->when(strlen($this->search) > 2, function ($builder) {
                $builder->where(function ($builder) {
                    $builder->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('street', 'like', '%' . $this->search . '%')
                        ->orWhere('city', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate(10);
        return view('livewire.admin.user.index',compact('users'));
    }

    public function mount(){
        $this->page = 1;
        $this->lists = new Collection();
        $this->loadData();
    }

    public function loadData()
    {
        $users = User::with(['country'])
            ->when(strlen($this->search) > 2, function ($builder) {
                $builder->where(function ($builder) {
                    $builder->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('street', 'like', '%' . $this->search . '%')
                        ->orWhere('city', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->take($this->perPage*$this->page)
            ->get();

        $this->loadDataList($users);
    }

    public function updatedSearch(){
        $this->page = 1;
        $this->loadData();
    }
}
