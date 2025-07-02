<?php

namespace App\Http\Livewire\Profile\Portfolio;

use App\Models\SellCertificate;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class MyPortfolio extends Component
{
    use WithPagination;
    protected $listeners   =   ['reRenderParent'];
    public User $user;
    public $totalValue;
    /**data table*/
    public $headers;
    public $sortByCol="name";
    public $sortOrder="ASC";
    public $search;

    private function headerConfig(){
        return [
            'project_types.type'=>'Type',
            'name'=>'Name',
            'quantity'=>'Total Quantity',
            'countries.name'=>'Country',
            'price'=>'Current value',
            'status'=>'Status',
            'remove_certificate'=>'Remove Certificate'
        ];
    }
    /**data table*/
    public function render(){
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
            ->where('user_id',$this->user->id)
            ->whereHas("certificate",function($q){
                $q->where("deleted_at", null);
            })
            ->where('remaining_units', '>', 0)
            ->orderBy('id', 'desc')->paginate(2);


        $totalValueRecord   =   SellCertificate::select(DB::raw('SUM(price_per_unit*remaining_units) as totalValue'))->with('certificate')
                                ->where('user_id',$this->user->id)
                                ->where('remaining_units','>',0)
                                ->first();

        $this->totalValue   =   $totalValueRecord->totalValue;
        return view('livewire.profile.portfolio.my-portfolio',compact('sellCertificates'));
    }
    public function mount(){
        $this->user     =   auth()->user();
        $this->headers  =   $this->headerConfig();
    }
    public function reRenderParent(){
        $this->mount();
        $this->render();
    }
    public function openDeleteCertificateModal($id){
        $this->emit('openDeleteCertificateModal',$id);
    }
    /**Data Table */
    public function sort($key){
        $order              =   $this->sortByCol ==$key?($this->sortOrder=="ASC"?"DESC":"ASC"):"ASC";
        $this->sortByCol    =   $key;
        $this->sortOrder    =   $order;
    }
    /**Data Table */
}
