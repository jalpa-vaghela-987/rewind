<?php

namespace App\Http\Livewire\Profile\Transaction;

use App\Http\Livewire\Table\Lists;
use App\Models\Subscription;
use Illuminate\Support\Collection;

class MyTransactions extends Lists
{
    public $search;

    public function render(){
        $transactions =   Subscription::with(['certificate', 'certificate.project_type', 'certificate.country', 'seller', 'buyer' ])
                            ->where(function ($query) {
                                $query->where('user_id', auth()->user()->id)
                                    ->orWhere('receiver_id', auth()->user()->id);
                            })
                            ->when($this->search, function ($builder) {
                                $builder->where(function ($builder) {
                                    $builder->where('name', 'like', '%' . $this->search . '%')
                                        ->orWhere('quantity', 'like', '%' . $this->search . '%')
                                        ->orWhere('amount', 'like', '%' . $this->search . '%')
                                        ->orWhereHas('certificate', function ($query) {
                                            $query->where('name', 'like', '%' . $this->search . '%');
                                        })
                                        ->orWhereHas('certificate.project_type', function ($query) {
                                            $query->where('type', 'like', '%' . $this->search . '%');
                                        })
                                        ->orWhereHas('certificate.country', function ($query) {
                                            $query->where('name', 'like', '%' . $this->search . '%');
                                        })
                                        ->orWhereHas('seller', function ($query) {
                                            $query->where('name', 'like', '%' . $this->search . '%');
                                        })
                                        ->orWhereHas('buyer', function ($query) {
                                        $query->where('name', 'like', '%' . $this->search . '%');
                                    });
                                });
                            })
                            ->where('stripe_status','success')

                                ->paginate(15);
        return view('livewire.profile.transaction.my-transactions',compact('transactions'));
    }
    public function mount(){
        $this->page = 1;
        $this->lists = new Collection();
        $this->loadData();
    }

    public function loadData()
    {
        $transactions =   Subscription::with(['certificate', 'certificate.project_type', 'certificate.country', 'seller', 'buyer' ])
            ->where(function ($query) {
                $query->where('user_id', auth()->user()->id)
                    ->orWhere('receiver_id', auth()->user()->id);
            })
            ->when($this->search, function ($builder) {
                $builder->where(function ($builder) {
                    $builder->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('quantity', 'like', '%' . $this->search . '%')
                        ->orWhere('amount', 'like', '%' . $this->search . '%')
                        ->orWhereHas('certificate', function ($query) {
                            $query->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('certificate.project_type', function ($query) {
                            $query->where('type', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('certificate.country', function ($query) {
                            $query->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('seller', function ($query) {
                            $query->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('buyer', function ($query) {
                            $query->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->where('stripe_status','success')
            ->take($this->perPage*$this->page)
            ->get();

        $this->loadDataList($transactions);
    }

    public function updatedSearch(){
        $this->page = 1;
        $this->loadData();
    }

}
