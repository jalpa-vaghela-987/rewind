<?php

namespace App\Http\Livewire\Profile\Detail\User;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProfilePictureUploadForm extends Component
{
    use WithFileUploads;
    protected $listeners =  ['openCloseProfilePictureModal','saveProfilePhoto'];
    public User $user;
    public $showModal = false;
    public $profile_photo;
    public function rules(){
        return  [
            'profile_photo' => ['required','string',function ($attribute, $value, $fail){
                if(!in_array(explode('.',$value)[1],['png', 'gif', 'bmp', 'svg','jpg', 'jpeg'])){
                    $fail('The profile photo must be an Image');
                }
            }],
        ];
    }
    public function render()
    {
        return view('livewire.profile.detail.user.profile-picture-upload-form');
    }
    public function mount(){
        $this->user = auth()->user();
    }
    /**
     * Save profile photo
    */
    public function saveProfilePhoto($data){
        $this->profile_photo            =   $data['name'];
        $this->validate();
        $full_name                      =   $data['name'];
        $ext                            =   pathinfo($full_name, PATHINFO_EXTENSION);
        $filename                       =   'profile_img_'. time() .'.'.$ext;
        $path                           =   'images/'.$this->user->id;
        /** */
        $image_parts    = explode(";base64,", $data['image']);
        $image_base64   = base64_decode($image_parts[1]);
        /*
        NOT TO DELETE
        file_put_contents(public_path('storage/').$path.'/'.$filename,$image_base64);
        */
        Storage::disk('public')->put($path.'/'.$filename, $image_base64);
        /** */
        $this->user->profile_photo_path =   $path.'/'.$filename;
        if($this->user->save()){
            $type = 'success';
            $msg = 'Profile photo updated successfully.';
        }else{
            $type = 'error';
            $msg = 'Something went wrong.';
        }
        $this->emitTo('flash-component', 'flashMessage',['type' => $type, 'msg' => $msg]);
        $this->emit('reRenderParent');
        $this->closeProfilePictureModal();
    }
    public function openCloseProfilePictureModal(){
        $this->showModal    =   true;
    }
    public function closeProfilePictureModal(){
        $this->showModal    =   false;
    }

}
