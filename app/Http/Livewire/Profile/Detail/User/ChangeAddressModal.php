<?php

namespace App\Http\Livewire\Profile\Detail\User;

use App\Models\Country;
use App\Models\User;
use App\Notifications\SendMessageNotification;
use Livewire\Component;

class ChangeAddressModal extends Component
{
    public $change_address=false;
    public User $user;
    public $countries;
    protected $listeners = ['openCloseAddressModel'=>'openCloseAddressModel'];
    public $rules   =   [
        'user.street' => 'required|string|max:500',
        'user.country_id' => 'required|integer|exists:countries,id',
        'user.city' => 'required|string|max:500',
    ];
    public function render()
    {
        $this->user         =   auth()->user();
        $this->countries    =   Country::select("name","id")->where('is_active',1)->get();
        return view('livewire.profile.detail.user.change-address-modal');
    }
    public function save(){
        $this->validate();
        $this->user->save();
        $this->emitTo('flash-component', 'flashMessage',['type' => 'success', 'msg' => 'Address Updated Successfully.']);
        activity()
        ->performedOn($this->user)
        ->causedBy(auth()->user())
        ->log('address with street: <b> :subject.street</b> city: <b>:subject.city</b> country: <b>:subject.country.name</b> has been Updated');
        $message = 'User Address Changed:<a href="'.route('admin.users').'">'.  $this->user->name. '</a>';
        $user = User::find(1);
        $user->notify(new SendMessageNotification($message));
        $this->emit('reRenderParent');
        $this->closeAddressModel();
    }
    public function openCloseAddressModel(){
        $this->change_address = true;
    }

    public function closeAddressModel()
    {
        $this->change_address = false;
    }
}
