<?php

namespace App\Http\Livewire\Profile\Portfolio;

use App\Models\SellCertificate;
use Livewire\Component;

class DeleteCertificateModal extends Component
{
    protected $listeners    =   ['openDeleteCertificateModal'];
    public SellCertificate $sell_certificate;
    public $showModal       =   false;
    public $is_agree        =   false;
    public $certificate_id;
    protected $rules = [
        'is_agree' => 'in:1',
    ];
    protected $messages = [
        'is_agree.in'     => 'Please accept the terms',
    ];

    public function render()
    {
        return view('livewire.profile.portfolio.delete-certificate-modal');
    }
    /**
     *
     */
    public function openDeleteCertificateModal($certificate_id){
        $sell_certificate    =   SellCertificate::find($certificate_id);
        if($sell_certificate){
            $this->sell_certificate  =   $sell_certificate;
            $this->showModal = true;
        }else{
            $type   =   "error";
            $msg    =   "Certificate not found";
            $this->emitTo('flash-component', 'flashMessage',['type' => $type, 'msg' => $msg]);
            $this->emitTo("MyPortfolio","reRenderComponent");
        }
    }

    public function closeDeleteCertificateModal(){
        $this->showModal = false;
    }
    /**
     *
    */
    public function deleteCertificate(){

        if ($this->sell_certificate->units == $this->sell_certificate->remaining_units){
            if($this->sell_certificate->certificate->delete()){
                $type = 'success';
                $msg = 'Certificate deleted successfully!';
                activity()
                    ->performedOn($this->sell_certificate->certificate)
                    ->causedBy(auth()->user())
                    ->log('certificate <b>:subject.name</b> has been deleted');
            }else{
                $type = 'error';
                $msg = 'Something went wrong!';
            }
        }else{
            $type = 'error';
            $msg = 'Something went wrong!';
        }
        $this->emitTo('flash-component', 'flashMessage',['type' => $type, 'msg' => $msg]);
        $this->closeDeleteCertificateModal();
        // $this->emit("reRenderParent");
        // $this->emit('refresh-bootstrap-table');
        // $this->dispatchBrowserEvent('refresh-bootstrap-table');
        return redirect()->route("profile");
    }
}
