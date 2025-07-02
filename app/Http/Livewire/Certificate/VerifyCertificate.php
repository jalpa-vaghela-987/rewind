<?php

namespace App\Http\Livewire\Certificate;

use App\Models\Certificate;
use App\Models\User;
use Livewire\Component;

class VerifyCertificate extends Component
{
    public $status;
    public $certificate_id;
    public function render()
    {
        $layout = auth()->check()?'layouts.app':'layouts.guest';
        return view('livewire.certificate.validate-certificate')->layout($layout);
    }
    public function mount(){

        $certificate = Certificate::find($this->certificate_id);
        if($this->status == 'Approve'){
            $certificate->status = 1;
        }else{
            $certificate->status = 2;
        }
        $certificate->save();
        activity()
        ->performedOn($certificate)
        ->causedBy(auth()->user())
        ->log('Certificate :subject.name has been verified');
        /*$user =   User::where('email_verification_string',$this->validate_str)->where('new_email','!=',null)->first();
        if($user){
            $user->email                        =   $user->new_email;
            $user->new_email                    =   null;
            $user->email_verification_string    =   null;
            $user->email_verified               =   true;
            $user->save();
        }else{
            session()->flash('warning', 'You\'re using wrong url!');
            return redirect()->route('login');
        }*/
    }
}
