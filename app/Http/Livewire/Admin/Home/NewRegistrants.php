<?php

namespace App\Http\Livewire\Admin\Home;

use App\Models\User;
use Livewire\Component;

class NewRegistrants extends Component
{
    public $new_registants;
    public $approval_user_id;
    protected $listeners =['approveRegistrant','reRenderParent'];
    public function render()
    {
        $this->new_registants    =   User::with('country')->role('user')->where('status',0)->orderBy('created_at','DESC')->take(3)->get();
        // echo "<pre>"; print_r($this->new_registants->toArray()); die;
        return view('livewire.admin.home.new-registrants');
    }
    public function confirmApproval($id){
        $this->approval_user_id     =   base64_decode($id);
        // $this->emit('showConfirmBox','approveRegistrant');
    }
    public function reRenderParent(){
        $this->render();
    }
}
