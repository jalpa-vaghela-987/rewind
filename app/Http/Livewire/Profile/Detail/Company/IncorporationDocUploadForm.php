<?php

namespace App\Http\Livewire\Profile\Detail\Company;

use App\Models\Company;
use App\Models\User;
use Illuminate\Validation\Rules\File;
use Livewire\Component;
use Livewire\WithFileUploads;

class IncorporationDocUploadForm extends Component
{
    use WithFileUploads;
    protected $listeners =  ['openCloseIncorporationDocModal'];
    public $showModal = false;
    public $incorporation_doc;
    public $is_incorporation_doc_img = true;
    public User $user;
    public Company $company;
    public $file_extension;
    public $acceptedMimes = ['png', 'gif', 'bmp', 'svg',
    'jpg', 'jpeg', 'webp','pdf'];
    public $imageMimes = ['png', 'gif', 'bmp', 'svg',
    'jpg', 'jpeg', 'webp'];
    public $fileMime = ['pdf'];
    protected function rules()
    {
        return [
            'incorporation_doc' => [File::types($this->acceptedMimes)->max(5 * 1024)],
        ];
    }
    public function render()
    {
        return view('livewire.profile.detail.company.incorporation-doc-upload-form');
    }
    public function mount(){
        $this->user     =   auth()->user();
        $this->company  =   $this->user->company;
    }
    public function openCloseIncorporationDocModal(){
        $this->resetErrorBag();
        $this->incorporation_doc =null;
        $this->showModal    =   !$this->showModal;
    }
    /**
     * Save profile photo
    */
    public function save(){
        $this->validate();
        $full_name                      =   $this->incorporation_doc->getClientOriginalName();
        $ext                            =   pathinfo($full_name, PATHINFO_EXTENSION);
        $filename                       =   'incorporation_doc_url'.'.'.$ext;
        $path                           =   'images/'.$this->user->id;
        $this->incorporation_doc->storeAs($path,$filename,'public');
        $this->company->incorporation_doc_url =   $path.'/'.$filename;
        $this->company->save();
        $type = 'success';
        $msg = 'Incorporation document updated successfully!';
        $this->emitTo('flash-component', 'flashMessage',['type' => $type, 'msg' => $msg]);
        activity()
        ->performedOn($this->company)
        ->causedBy(auth()->user())
        ->log('Incorporation document updated successfully');
        $this->openCloseIncorporationDocModal();
        $this->emit('reRenderParent');
    }
    public function updated($prop){
        if($prop == 'incorporation_doc'){
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
}
