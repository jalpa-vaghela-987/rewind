<?php

namespace App\Http\Livewire\Portfolio;

use App\Http\Livewire\Table\Lists;
use App\Models\SellCertificate;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Index extends Lists
{

    protected $listeners = ['reRenderParent'];
    public User $user;
    public $totalValue;
    /**data table*/
    public $headers;
    public $sortByCol = "name";
    public $sortOrder = "ASC";
    public $search;
    public $certificatePage;

    private function headerConfig()
    {
        return [
            'project_types.type' => 'Type',
            'name' => 'Name',
            'quantity' => 'Total Quantity',
            'countries.name' => 'Country',
            'price' => 'Current value',
            'status' => 'Status',
            'remove_certificate'=>'Remove carbon credits'
        ];
    }
    /**data table*/
    public function render()
    {
        $sellCertificates = SellCertificate::with('certificate')
            ->when($this->search, function ($builder) {
                $builder->where(function ($builder) {
                    $builder->orWhereHas('certificate', function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                    });
                    $builder->orWhere('remaining_units', 'like', '%' . $this->search . '%');
                    $builder->orWhere('price_per_unit', 'like', '%' . $this->search . '%');
                    $builder->orWhereHas('certificate.project_type', function ($query) {
                        $query->where('type', 'like', '%' . $this->search . '%');
                    });
                    $builder->orWhereHas('certificate.country', function ($query) {

                        $query->where('name', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->where('user_id', $this->user->id)
            ->whereHas("certificate", function ($q) {
                $q->where("deleted_at", null);
            })
            ->where('remaining_units', '>', 0)
            ->orderBy('id', 'desc')->paginate(15);


        $totalValueRecord = SellCertificate::select(DB::raw('SUM(price_per_unit*remaining_units) as totalValue'))->with('certificate')
            ->where('user_id', $this->user->id)
            ->where('remaining_units', '>', 0)
            ->first();

        $this->totalValue = $totalValueRecord->totalValue;
        return view('livewire.portfolio.index', compact('sellCertificates'));
    }

    public function mount()
    {
        $this->user = auth()->user();
        $this->headers = $this->headerConfig();
        $this->page=1;
        $this->lists = new Collection();
        $this->loadData();
    }

    public function reRenderParent()
    {
        $this->page=1;
        $this->lists = new Collection();
        $this->loadData();
        $this->render();
    }

    public function openDeleteCertificateModal($id)
    {
        $this->emit('openDeleteCertificateModal', $id);
    }

    /**Data Table */
    public function sort($key)
    {
        $order = $this->sortByCol == $key ? ($this->sortOrder == "ASC" ? "DESC" : "ASC") : "ASC";
        $this->sortByCol = $key;
        $this->sortOrder = $order;
    }

    public function loadData()
    {
        $sell_certificates = SellCertificate::query()->with('certificate', 'certificate.project_type')
            ->when($this->search, function ($builder) {
                $builder->where(function ($builder) {
                    $builder->orWhereHas('certificate', function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%');
                    });
                    $builder->orWhere('remaining_units', 'like', '%' . $this->search . '%');
                    $builder->orWhere('price_per_unit', 'like', '%' . $this->search . '%');
                    $builder->orWhereHas('certificate.project_type', function ($query) {
                        $query->where('type', 'like', '%' . $this->search . '%');
                    });
                    $builder->orWhereHas('certificate.country', function ($query) {

                        $query->where('name', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->where('user_id', $this->user->id)
            ->whereHas("certificate", function ($q) {
                $q->where("deleted_at", null);
            })
            ->where('remaining_units', '>', 0)
            ->orderBy('id', 'desc')
            ->take($this->perPage*$this->page)
            ->get();

        $this->loadDataList($sell_certificates);
        $this->certificatePage++;

    }

    public function updatedSearch(){
        $this->page = 1;
        $this->loadData();
    }
}
