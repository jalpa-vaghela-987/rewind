<?php

namespace App\Http\Livewire\Profile\Detail\Company;

use App\Models\Company;
use App\Models\User;
use App\Notifications\SendMessageNotification;
use Illuminate\Validation\Rules\File;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddNewCompanyModal extends Component
{
    use WithFileUploads;
    protected Company $company;
    public $name,$registration_id,$field_of_business,$showModal,$incorporation_document,$is_incorporation_doc_img,$file_extension;
    public $acceptedMimes = ['png', 'gif', 'bmp', 'svg',
    'jpg', 'jpeg', 'webp','pdf'];
    public $imageMimes = ['png', 'gif', 'bmp', 'svg',
    'jpg', 'jpeg', 'webp'];
    public $fileMime = ['pdf'];
    protected $listeners = ['openCloseAddNewCompanyModal','reRenderParent'];
    public function render()
    {
        return view('livewire.profile.detail.company.add-new-company-modal');
    }
    protected function rules()
    {
        return [
            'name' => 'required|string',
            'registration_id' => 'required|numeric',
            'field_of_business' => 'required|string',
            'incorporation_document' => ['required',File::types($this->acceptedMimes)->max(5 * 1024)],
        ];
    }
    public function saveCompanyDetails(){
        $this->validate();
        $this->company                      =   new Company();
        $this->company->user_id             =   auth()->user()->id;
        $this->company->name                =   $this->name;
        $this->company->field               =   $this->field_of_business;
        $this->company->registration_id     =   $this->registration_id;
        if($this->incorporation_document){
            $full_name                      =   $this->incorporation_document->getClientOriginalName();
            $ext                            =   pathinfo($full_name, PATHINFO_EXTENSION);
            $filename                       =   'incorporation_doc_url'.'.'.$ext;
            $path                           =   'images/'.auth()->user()->id;
            $this->incorporation_document->storeAs($path,$filename,'public');
            $this->company->incorporation_doc_url =   $path.'/'.$filename;
        }
        if($this->company->save()){
            $type = 'success';
            $msg = 'Company details saved successfully!';
        }else{
            $type = 'error';
            $msg = 'Technical error, Please try again later!';
        }
        $message = 'New Company Added By:<a href="'.route('admin.users').'">'.  auth()->user()->name. '</a>';
        $user = User::find(1);
        $user->notify(new SendMessageNotification($message));
        $this->reset();
        $this->emitTo('change-company-address-modal','openCloseNewCompanyAddressModel');
        $this->emitTo('flash-component', 'flashMessage',['type' => $type, 'msg' => $msg]);
    }
    public function openCloseAddNewCompanyModal(){
        $this->showModal    =   !$this->showModal;
        $this->name = null;
        $this->registration_id = null;
        $this->field_of_business = null;
        $this->incorporation_document = null;
        $this->resetValidation();
        $this->mount();
    }
    public function updated($prop){
        if($prop == 'incorporation_document'){
            $f_name                      =   $this->$prop->getClientOriginalName();
            $ext                         =   pathinfo($f_name, PATHINFO_EXTENSION);
            if(in_array($ext,$this->imageMimes)){
                $this->is_incorporation_doc_img   =   true;
            }else{
                $this->is_incorporation_doc_img   =   false;
            }
            $this->file_extension           =   $ext;
        }
        $this->validateOnly($prop);
    }
    public function reRenderParent(){
        $this->mount();
        $this->render();
    }
}
