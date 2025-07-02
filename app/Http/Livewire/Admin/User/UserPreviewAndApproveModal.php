<?php

namespace App\Http\Livewire\Admin\User;

use App\Mail\ApproveUserMail;
use App\Models\Company;
use App\Models\User;
use App\Notifications\SendMessageNotification;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class UserPreviewAndApproveModal extends Component
{
    public $showModal=false;
    public $is_incorporation_doc_img = false;
    public $user_id,$address,$company_address;
    public $user,$company;
    protected $listeners    =   ['showPreviewModal', 'openApprovalModal'];
    public $file_extension;
    public $imageMimes = ['png', 'gif', 'bmp', 'svg',
    'jpg', 'jpeg', 'webp'];
    public $fileMime = ['pdf'];
    public $verifyMsg = '';
    public $verifyType = '';
    public $activeTab = 'user_details';
    public $userId, $name, $email, $phone, $id_proof, $status;
    public $selectedUserCompany = null;
    public $viewUser = false;


    public function render()
    {
        $this->user         =   User::find($this->user_id);
        if($this->user){
            $this->address  =   $this->user->street;
            if($this->user->city){
                $this->address  .= ', '.$this->user->city;
            }
            if($this->user->country){
                $this->address  .= ', '.$this->user->country->name;
            }
            $this->company          =   $this->user->company;
            if($this->company){
                if(!empty($this->company->incorporation_doc_url)){
                    $ext                        =   pathinfo($this->company->incorporation_doc_url, PATHINFO_EXTENSION);
                    $this->file_extension       =   $ext;
                    if(in_array($ext,$this->imageMimes)){
                        $this->is_incorporation_doc_img   =   true;
                    }
                }
                $this->company_address  =   $this->company->street;
                if($this->company->city){
                    $this->company_address  .= ', '.$this->company->city;
                }
                if($this->company->country){
                    $this->company_address  .= ', '.$this->company->country->name;
                }
            }
        }
        return view('livewire.admin.user.user-preview-and-approve-modal');
    }

    public function showPreviewModal($id){
        $this->user_id      =   base64_decode($id);
        $user  = User::find($this->user_id);
        $this->openApprovalModal($user, $this->activeTab);
    }

    public function approveRegistrant(){
        $id     =   $this->user_id;
        $update =   User::where('id',$id)->where('status',0)->update(['status'=>true]);
        if($update){
            $type = 'success';
            $msg = 'Account Approved successfully.';
        }else{
            $type = 'warning';
            $msg = 'User not found.';
        }
        $this->emitTo('flash-component', 'flashMessage',['type' => $type, 'msg' => $msg]);
        $this->reset();
        $this->emit('reRenderParent');
    }

    public function openApprovalModal(User $user, $activeTab)
    {
        $this->activeTab = $activeTab;
        $this->verifyType = '';
        $this->verifyMsg = '';
        $this->viewUser = !$this->viewUser;
        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;

        $this->address  =   $user->street;
        if($user->city){
            $this->address  .= ', '.$user->city;
        }
        if($user->country){
            $this->address  .= ', '.$user->country->name;
        }
        $this->phone = $user->phone;
        $this->id_proof = $user->id_proof;
        $this->status = $user->status;
        $this->selectedUserCompany = $user->company;
    }

    public function closeApprovalModal()
    {
        $this->viewUser = !$this->viewUser;
    }

    public function approveUser($id)
    {
        $user = User::find($id);
        $user->status = 1;
        $user->save();
        Mail::to($user->email)->send(new ApproveUserMail($user));
        $this->verifyMsg = 'User status change as <strong>Approved</strong> successfully';
        $this->verifyType = 'success';
        $msg = 'Your Profile is approved';
        $user->notify(new SendMessageNotification($msg,'profile'));
        $this->dispatchBrowserEvent('reloadPage');
    }

    public function declineUser($id)
    {
        $user = User::find($id);
        $user->status = 2;
        $user->save();
        $this->verifyMsg = 'User status change as <strong>Decline</strong> successfully';
        $this->verifyType = 'success';
        $msg = 'Your Profile is decline';
        $user->notify(new SendMessageNotification($msg,'profile'));
        $this->dispatchBrowserEvent('reloadPage');
    }

    public function verifyCompany($id, $status)
    {
        $company = Company::find($id);
        Company::where('id', $id)->update(['status' => $status]);
        $this->verifyType = 'success';
        if($status == 1) {
            $msg = 'Your Company is approved';
            $this->verifyMsg = 'Company status change as <strong>Approved</strong> successfully';
        } else {
            $msg = 'Your Company is decline';
            $this->verifyMsg = 'Company status change as <strong>Decline</strong> successfully';
        }
        $company->user->notify(new SendMessageNotification($msg,'profile'));
        $this->dispatchBrowserEvent('reloadPage');
    }
}
