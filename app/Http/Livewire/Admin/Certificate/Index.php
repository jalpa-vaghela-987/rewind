<?php

namespace App\Http\Livewire\Admin\Certificate;

use App\Http\Livewire\Table\Lists;
use App\Models\Certificate;
use App\Notifications\SendMessageNotification;
use Illuminate\Support\Collection;

class Index extends Lists
{
    public Certificate $certificate;
    public $certificateId, $title, $projectType, $country, $price, $quantity, $approving_body, $link_to_certificate, $status;
    public $viewCertificate = false;
    public $selectedCertificate = null;
    public $search = '';
    public $project_type_id;
    public $country_id;
    public $project_year;
    public $vintage;
    public $total_size;
    public $lattitude;
    public $longitude;
    public $verify_by;
    public $registry_id;

    public function render()
    {
        $certificates = Certificate::when(strlen($this->search) > 2, function ($builder) {
            $builder->where(function ($builder) {
                $builder->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('quantity', 'like', '%' . $this->search . '%')
                ->orWhere('status', 'like', '%' . $this->search . '%')
                ->orWhere('price', 'like', '%' . $this->search . '%')
                ->orWhereHas('project_type',function($query){
                    $query->where('type', 'like', '%' . $this->search . '%');
                });

            });
        })
        ->orderBy('id','desc')->paginate(10);
        return view('livewire.admin.certificate.index', compact('certificates'));
    }

    public function openModal(Certificate $certificate)
    {
        $this->viewCertificate = !$this->viewCertificate;
        $this->certificateId = $certificate->id;
        $this->title = $certificate->name;
        $this->projectType = $certificate->project_type->type;
        $this->country = $certificate->country->name;
        $this->quantity = $certificate->quantity;
        $this->price = $certificate->price;
        $this->approving_body = $certificate->approving_body;
        $this->status = $certificate->status;
        $this->link_to_certificate = $certificate->link_to_certificate;
        $this->project_year = $certificate->project_year;
        $this->vintage = $certificate->vintage;
        $this->total_size = $certificate->total_size;
        $this->lattitude = $certificate->lattitude;
        $this->longitude = $certificate->longitude;
        $this->verify_by = $certificate->verify_by;
        $this->registry_id = $certificate->registry_id;

    }

    public function closeModal()
    {
        $this->viewCertificate = !$this->viewCertificate;
    }

    public function declineCertificate($id)
    {
        $certificate = Certificate::find($id);
        $certificate->status = 4;
        $certificate->save();

        $sellCertificate = $certificate->sell_certificate()->where('is_main', true)->first();
        $sellCertificate->status = 4;
        $sellCertificate->save();

        $this->viewCertificate = !$this->viewCertificate;
        $msg = 'Admin Decline for this carbon credit: <a href="'.route('sell.show.certificate',$sellCertificate->id).'">'.  $certificate->name. '</a>';
        $certificate->user->notify(new SendMessageNotification($msg));
    }

    public function showCertificate($id)
    {

        $certificate = Certificate::find($id);
        $certificate->lattitude =  $this->lattitude;
        $certificate->longitude =  $this->longitude;
        $certificate->verify_by =  $this->verify_by;
        $certificate->registry_id =  $this->registry_id;
        $certificate->save();
        $this->viewCertificate = !$this->viewCertificate;
        return redirect()->to("admin/certificate/$id");
    }

    public function mount(){
        $this->page = 1;
        $this->lists = new Collection();
        $this->loadData();
    }

    public function loadData()
    {
        $certificates = Certificate::when(strlen($this->search) > 2, function ($builder) {
            $builder->where(function ($builder) {
                $builder->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('quantity', 'like', '%' . $this->search . '%')
                    ->orWhere('status', 'like', '%' . $this->search . '%')
                    ->orWhere('price', 'like', '%' . $this->search . '%')
                    ->orWhereHas('project_type',function($query){
                        $query->where('type', 'like', '%' . $this->search . '%');
                    });

            });
        })
            ->orderBy('id','desc')
            ->take($this->perPage*$this->page)
            ->get();

        $this->loadDataList($certificates);
    }

    public function updatedSearch(){
        $this->page = 1;
        $this->loadData();
    }


}
