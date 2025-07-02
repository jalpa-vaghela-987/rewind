<?php

namespace App\Http\Livewire\Admin\Deal;

use App\Http\Livewire\Table\Lists;
use App\Models\Subscription;
use Illuminate\Support\Collection;

class Index extends Lists
{
    public Subscription $subscription;
    public $search;
    protected $queryString = ['search'];
    public function render()
    {
        $deals = Subscription::with(['certificate', 'certificate.project_type', 'certificate.country', 'seller', 'buyer'  ])
            ->when(strlen($this->search) > 2, function ($builder) {
            $builder->where(function ($builder) {
                $builder->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('quantity', 'like', '%' . $this->search . '%')
                    ->orWhere('amount', 'like', '%' . $this->search . '%')
                    ->orWhereHas('certificate',function($query){
                        $query->where('name', 'like', '%' . $this->search . '%');
                    })->orWhereHas('certificate.project_type',function($query){
                        $query->where('type', 'like', '%' . $this->search . '%');
                    })->orWhereHas('certificate.country',function($query){
                        $query->where('name', 'like', '%' . $this->search . '%');
                    })->orWhereHas('seller',function($query){
                        $query->where('name', 'like', '%' . $this->search . '%');
                    })->orWhereHas('buyer',function($query){
                        $query->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        })
            ->orderBy('id','desc')->paginate(10);


        return view('livewire.admin.deal.index', compact('deals'));
    }

    public function mount(){
        $this->page = 1;
        $this->lists = new Collection();
        $this->loadData();
    }

    public function loadData()
    {
        $deals = Subscription::with(['certificate', 'certificate.project_type', 'certificate.country', 'seller', 'buyer'  ])
            ->when(strlen($this->search) > 2, function ($builder) {
                $builder->where(function ($builder) {
                    $builder->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('quantity', 'like', '%' . $this->search . '%')
                        ->orWhere('amount', 'like', '%' . $this->search . '%')
                        ->orWhereHas('certificate',function($query){
                            $query->where('name', 'like', '%' . $this->search . '%');
                        })->orWhereHas('certificate.project_type',function($query){
                            $query->where('type', 'like', '%' . $this->search . '%');
                        })->orWhereHas('certificate.country',function($query){
                            $query->where('name', 'like', '%' . $this->search . '%');
                        })->orWhereHas('seller',function($query){
                            $query->where('name', 'like', '%' . $this->search . '%');
                        })->orWhereHas('buyer',function($query){
                            $query->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->take($this->perPage*$this->page)
            ->get();

        $this->loadDataList($deals);
    }

    public function updatedSearch(){
        $this->page = 1;
        $this->loadData();
    }

}
