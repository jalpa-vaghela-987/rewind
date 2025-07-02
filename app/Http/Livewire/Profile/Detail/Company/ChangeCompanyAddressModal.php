<?php

namespace App\Http\Livewire\Profile\Detail\Company;

use App\Models\Company;
use App\Models\Country;
use App\Models\User;
use App\Notifications\SendMessageNotification;
use Livewire\Component;

class ChangeCompanyAddressModal extends Component
{
    public $change_address=false,$new_company=false;
    public Company $company;
    public $countries;
    public $street = null, $country_id = null, $city = null;
    protected $listeners = ['openCloseCompanyAddressModel'=>'openCloseAddressModel','openCloseNewCompanyAddressModel'=>'openCloseNewCompanyAddressModel'];
    public $rules   =   [
        'street' => 'required|string|max:500',
        'country_id' => 'required|integer|exists:countries,id',
        'city' => 'required|string|max:500',
    ];
    public function render()
    {

        $this->company      =   auth()->user()->company?auth()->user()->company:new Company();
        $this->countries    =   Country::select("name","id")->where('is_active',1)->get();
        return view('livewire.profile.detail.company.change-company-address-modal');
    }
    public function save(){
        $this->validate();
        $company = $this->company;
        $company->street = $this->street;
        $company->country_id = $this->country_id;
        $company->city = $this->city;
        $company->save();

        $this->openCloseAddressModel();
        activity()
        ->performedOn($this->company)
        ->causedBy(auth()->user())
        ->withProperties(['street' => $this->company->street, 'city' => $this->company->street,'country'=>$this->company->country->name])
        ->log('Company address with street: <b> :subject.street</b> city: <b>:subject.city</b> country: <b>:subject.country.name</b> has been Updated');

        $message = 'Company Address Changed by:<a href="'.route('admin.users').'">'.  auth()->user()->name. '</a>';
        $user = User::find(1);
        $user->notify(new SendMessageNotification($message));
        if($this->new_company){
            $this->emitTo('new-company-detail-success-modal','showHideSuccessModal');
        }else{
            $this->emit('reRenderParent');
        }
    }
    public function openCloseAddressModel(){
        $this->change_address = !$this->change_address;
    }
    public function openCloseNewCompanyAddressModel(){
        $this->new_company      =   true;
        $this->change_address   =   !$this->change_address;
    }
}
