<?php

namespace App\Http\Livewire\Profile\Detail\User;

use App\Models\User;
use App\Notifications\SendMessageNotification;
use Livewire\Component;
use Livewire\WithFileUploads;

class UserDetailForm extends Component
{
    use WithFileUploads;
    public User $user;
    public $nameDisabled=true;
    public $id_proof;
    public $change_address = false;
    public $address;
    public $disabled = [
        "name"=> true,
        "email"=> true,
        "id_proof"=>true,
    ];
    protected $listeners = [
        "refresh"=>'$refresh',
        "reRenderParent"=>'reRenderParent',
    ];
    protected $rules = [
        'user.name' => 'required|string',
        'user.email' => 'required|string|max:500',
        // 'phone'     => 'required|unique:users,phone|digits:10|numeric',
        'id_proof' => 'nullable|image|max:5120',
    ];
    protected $messages = [
        // 'phone.unique'     => 'This phone number is already in use',
    ];
    /**
     * render user detail view
    */
    public function render()
    {
        $this->address  =   $this->user->street;
        if($this->user->city){
            $this->address  .= ', '.$this->user->city;
        }
        if($this->user->country){
            $this->address  .= ', '.$this->user->country->name;
        }

        return view('livewire.profile.detail.user.user-detail-form');
    }
    /**
     * update mount user info
     */
    public function mount(){
        $this->user =   auth()->user();
    }
    public function makeEditable($field="name"){
        $this->disabled[$field] = false;
    }
    // /**
    //  * Save profile photo
    // */
    // public function saveProfilePhoto(){
    //     $this->validateOnly('profile_photo');
    //     $full_name                      =   $this->profile_photo->getClientOriginalName();
    //     $ext                            =   pathinfo($full_name, PATHINFO_EXTENSION);
    //     $filename                       =   'profile_img'.'.'.$ext;
    //     $path                           =   'images/'.$this->user->id;
    //     $this->profile_photo->storeAs($path,$filename,'public');
    //     $this->user->profile_photo_path =   $path.'/'.$filename;
    //     if($this->user->save()){
    //         $type = 'success';
    //         $msg = 'Profile photo updated successfully.';
    //         $this->disabled['profile_photo'] = true;
    //     }else{
    //         $type = 'error';
    //         $msg = 'Something went wrong.';
    //         $this->disabled['profile_photo'] = true;
    //     }
    //     $this->emitTo('flash-component', 'flashMessage',['type' => $type, 'msg' => $msg]);
    //     $this->emit('reRenderParent');
    // }
    /**
     * Save Name
    */
    public function saveName(){
        $this->validateOnly('user.name');
        if($this->user->save()){
            $type = 'success';
            $msg = 'Name updated successfully.';
        }else{
            $type = 'error';
            $msg = 'Technical error, Please try again later.';
        }
        $message = 'User Name Changed:<a href="'.route('admin.users').'">'.  $this->user->name. '</a>';
        $user = User::find(1);
        $user->notify(new SendMessageNotification($message));
        $this->emitTo('flash-component', 'flashMessage',['type' => $type, 'msg' => $msg]);
        $this->disabled['name'] = true;
        $this->emit('reRenderParent');
    }
    public function reRenderParent(){
        $this->mount();
        $this->render();
    }
}
