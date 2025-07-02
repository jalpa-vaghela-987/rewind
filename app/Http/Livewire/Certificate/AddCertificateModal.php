<?php

namespace App\Http\Livewire\Certificate;

use App\Models\CardDetail;
use App\Models\Certificate;
use App\Models\Country;
use App\Models\ProjectType;
use App\Models\SellCertificate;
use App\Models\User;
use App\Notifications\SendMessageNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class AddCertificateModal extends Component
{
    protected $listeners = ["openCloseAddCertificateModal"];
    public $showModal = false;
    public $project_type_id;
    public $name;
    public $country_id;
    public $quantity;
    public $price;
    public $price_per_unit = 0;
    public $link_to_certificate;
    public $approving_body;
    public $certificate;
    public $countries;
    public $projectTypes;
    public $project_year;
    public $vintage;
    public $total_size;

    public function rules()
    {
        $this->price_per_unit = ($this->quantity>0) ? round($this->price/$this->quantity,2) : 0;

        $rules = [
            'project_type_id' => ['required'],
            'name' => ['required'],
            'country_id' => ['required', 'integer'],
            'quantity' => ['required', 'integer'],
            'price' => ['required', 'numeric','gte:1'],
            'price_per_unit' =>  ['required'],
            'link_to_certificate' => ['nullable'],
            'project_year' => ['nullable'],
            'vintage' => ['nullable'],
            'total_size' => ['nullable'],
        ];

        if($this->price) {
            $rules['price_per_unit'] =  ['required', 'numeric', 'gte:1'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'project_type_id.required' => "Project type required.",
            'name.required' => 'Project name required.',
            'country_id.required' => 'Country required.',
            'quantity.required' => 'Quantity required.',
            'quantity.integer' => 'Quantity should be integer.',
            'price.required' => 'Total price required.',
            'price_per_unit.gte' =>  'The price per unit is '.$this->price_per_unit.', This must be greater than or equal to 1.',
            'link_to_certificate.url' => 'Invalid url'

        ];
    }

    public function render()
    {
        $this->user = auth()->user();
        $this->countries = Country::select("name", "id")->where('is_active', 1)->get();
        $this->projectTypes = ProjectType::select("type", "id")->where('is_active', 1)->get();
        return view('livewire.certificate.add-certificate-modal');
    }

    public function openCloseAddCertificateModal()
    {
        $this->showModal = true;
        $this->reset([
            'project_type_id',
            'name',
            'country_id',
            'quantity',
            'price',
            'link_to_certificate',
            'approving_body',
            'certificate',
            'countries',
            'projectTypes',
            'project_year',
            'vintage',
            'total_size'
        ]);
    }

    public function closeAddCertificateModal()
    {
        $this->showModal = false;
        $this->reset([
            'project_type_id',
            'name',
            'country_id',
            'quantity',
            'price',
            'link_to_certificate',
            'approving_body',
            'certificate',
            'countries',
            'projectTypes',
            'project_year',
            'vintage',
            'total_size'
        ]);
    }

    public function save()
    {
        $this->validate();

        $saved = Certificate::create([
            'project_type_id' => $this->project_type_id,
            'name' => $this->name,
            'country_id' => $this->country_id,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'approving_body' => $this->approving_body,
            'link_to_certificate' => $this->link_to_certificate,
            'user_id' => auth()->user()->id,
            'status' => 1,
            'project_year' => $this->project_year,
            'vintage' => $this->vintage,
            'total_size' => $this->total_size,
        ]);

        $sellCertificate = SellCertificate::create([
            'certificate_id' => $saved->id,
            'user_id' => auth()->id(),
            'units' => $this->quantity,
            'remaining_units' => $this->quantity,
            'price_per_unit' => round($this->price/$this->quantity,2),
            'is_main' => true,
            'status' => 1,
        ]);

        $admin = User::role(ROLE_ADMIN)->first();

        $approve_url = route('validateCertificate', ['certificate_id' => $saved->id, 'status' => 'Approve']);
        $decline_url = route('validateCertificate', ['certificate_id' => $saved->id, 'status' => 'Decline']);
        $details['title'] = "Please verify your email";
        $details['approve_url'] = $approve_url;
        $details['decline_url'] = $decline_url;
        $details['id'] = $saved->id;
        $details['body'] = 'Hello,New Certificate has been added, Please check the details and approve or decline it.';
        // TEMPORARY COMMENT
        // Mail::to($admin->email)->send(new \App\Mail\CertificateVerifyMail($details));
        activity()
            ->performedOn($sellCertificate)
            ->causedBy(auth()->user())
            ->log('A <b>:subject.certificate.project_type.type</b> type certificate has been uploaded');
        session()->flash('flash.bannerStyle', 'success');
        session()->flash('flash.banner', 'Certificate Added Successfully.');
        $msg = 'A seller added new carbon credit: <a href="'.route('admin.certificates').'">'.  $sellCertificate->certificate->name. '</a>';
        $user = User::find(1);
        $user->notify(new SendMessageNotification($msg));
        $this->closeAddCertificateModal();
        $this->emit('reRenderParent');
    }
}
