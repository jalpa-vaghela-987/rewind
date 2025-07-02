<?php

namespace App\Http\Livewire\Profile\Detail\Company;

use Livewire\Component;

class NewCompanyDetailSuccessModal extends Component
{
    public $showModal       =   false;
    protected $listeners    =   [
        "showHideSuccessModal"=>"showHideSuccessModal",
        "refresh"=>'$refresh'
    ];

    public function render()
    {
        return view('livewire.profile.detail.company.new-company-detail-success-modal');
    }
    public function showHideSuccessModal(){
        $this->showModal    =    !$this->showModal;
    }
    public function hideSuccessModal(){
        $this->showModal    =    !$this->showModal;
        $this->emitTo('profile','reRenderParent');
    }
}
