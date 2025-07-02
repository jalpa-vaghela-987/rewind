<?php

namespace App\Http\Livewire\Profile\Detail\Company;

use App\Models\Company;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;

class CompanyDetailForm extends Component
{
    use WithFileUploads;
    public User $user;
    public Company $company;
    public $is_incorporation_doc_img = false;
    public $company_name;
    public $company_field;
    public $registration_id;
    public $address;
    public $file_extension;
    public $imageMimes = ['png', 'gif', 'bmp', 'svg',
    'jpg', 'jpeg', 'webp'];
    public $fileMime = ['pdf'];
    public $disabled = [
        "name"=> true,
        "field"=>true,
    ];
    protected $listeners = [
        "refresh"=>'$refresh',
        "reRenderParent"=>"reRenderParent"
    ];
    protected function rules()
    {
        return [
            'company_name' => 'required|string',
            'company_field' => 'required|string',
        ];
    }
    public function render()
    {
        $this->user             =   auth()->user();
        $this->company          =   $this->user->company;
        $this->registration_id  =   $this->company->registration_id;
        if(!empty($this->company->incorporation_doc_url)){
            $ext                        =   pathinfo($this->company->incorporation_doc_url, PATHINFO_EXTENSION);
            $this->file_extension       =   $ext;
            if(in_array($ext,$this->imageMimes)){
                $this->is_incorporation_doc_img   =   true;
            }
        }
        $this->address  =   $this->company->street;
        if($this->company->city){
            $this->address  .= ', '.$this->company->city;
        }
        if($this->company->country){
            $this->address  .= ', '.$this->company->country->name;
        }
        return view('livewire.profile.detail.company.company-detail-form');
    }
    public function mount(){
        $this->user             =   auth()->user();
        $this->company          =   $this->user->company;
        $this->company_name     =   $this->user->company->name;
        $this->company_field     =   $this->user->company->field;
    }
    public function makeEditable($field){
        $this->mount();
        $this->disabled[$field] = false;
        foreach ($this->disabled as $key => $flag) {
            if($key!=$field){
                $this->disabled[$key]= true;
            }
        }
    }
    /**
     * update user info
     */
    /*public function save(){
        $this->validate();
        $this->company->save();
        session()->flash('success', 'Company details updated successfully.');
        activity()
        ->performedOn($this->company)
        ->causedBy(auth()->user())
        ->log('company details has been Updated');
        foreach($this->disabled as $fieldname => $flag){
            $this->disabled[$fieldname] = true;
        }
        $this->emitSelf('refresh');
        // return redirect()->route('profile');
        // $this->mount();
    }*/
    public function saveCompanyName(){
        if($this->company->status){
            $this->validateOnly('company_name');
            $this->company->name = $this->company_name;
            if($this->company->save()){
                $type = 'success';
                $msg = 'Company name updated successfully!';
            }else{
                $type = 'error';
                $msg = 'Technical error, Please try again later!';
            }
            $this->emitTo('flash-component', 'flashMessage',['type' => $type, 'msg' => $msg]);
            $this->disabled['name'] = true;
            $this->reRenderParent();
        }else{
            $type = 'error';
            $msg = 'Technical error, Please try again later!';
            $this->emitTo('flash-component', 'flashMessage',['type' => $type, 'msg' => $msg]);
        }
    }
    public function reRenderParent(){
        $this->mount();
        $this->render();
    }
    public function saveCompanyField(){
        if($this->company->status){
            $this->validateOnly('company_field');
            $this->company->field = $this->company_field;
            if($this->company->save()){
                $type = 'success';
                $msg = 'Company field of business updated successfully!';
            }else{
                $type = 'error';
                $msg = 'Technical error, Please try again later!';
            }
            $this->emitTo('flash-component', 'flashMessage',['type' => $type, 'msg' => $msg]);
            $this->disabled['field'] = true;
            $this->reRenderParent();
        }else{
            $type = 'error';
            $msg = 'Technical error, Please try again later!';
            $this->emitTo('flash-component', 'flashMessage',['type' => $type, 'msg' => $msg]);
        }
    }
}
