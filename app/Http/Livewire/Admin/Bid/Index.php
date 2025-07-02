<?php

namespace App\Http\Livewire\Admin\Bid;

use App\Http\Livewire\Table\Lists;
use App\Models\Bid;
use Illuminate\Support\Collection;

class Index extends Lists
{

    public Bid $bid;
    public $search;
    protected $queryString = ['search'];

    public function render()
    {
        $bids = Bid::with('certificate', 'certificate.project_type', 'certificate.country', 'user')
            ->when(strlen($this->search) > 2, function ($builder) {
                    $builder->where(function ($builder) {
                        $builder
                            ->orwhere('unit', 'like', '%' . $this->search . '%')
                            ->orWhere('amount', 'like', '%' . $this->search . '%')
                            ->orWhereHas('certificate',function($query){
                                $query->where('name', 'like', '%' . $this->search . '%');
                            })
                            ->orWhereHas('certificate.project_type',function($query){
                                $query->where('type', 'like', '%' . $this->search . '%');
                            })
                            ->orWhereHas('certificate.country',function($query){
                                $query->where('name', 'like', '%' . $this->search . '%');
                            })
                            ->orWhereHas('user',function($query){
                                $query->where('name', 'like', '%' . $this->search . '%');
                            });
                    });
            })
            ->orderBy('id','desc')->paginate(10);

        return view('livewire.admin.bid.index', compact('bids'));
    }

    public function mount(){
        $this->page = 1;
        $this->lists = new Collection();
        $this->loadData();
    }

    public function loadData()
    {
        $bids = Bid::with('certificate', 'certificate.project_type', 'certificate.country', 'user')
            ->when(strlen($this->search) > 2, function ($builder) {
                $builder->where(function ($builder) {
                    $builder
                        ->orwhere('unit', 'like', '%' . $this->search . '%')
                        ->orWhere('amount', 'like', '%' . $this->search . '%')
                        ->orWhereHas('certificate',function($query){
                            $query->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('certificate.project_type',function($query){
                            $query->where('type', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('certificate.country',function($query){
                            $query->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('user',function($query){
                            $query->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->orderBy('id','desc')
            ->take($this->perPage*$this->page)
            ->get();

        $this->loadDataList($bids);
    }

    public function updatedSearch(){
        $this->page = 1;
        $this->loadData();
    }

}
