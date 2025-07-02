<?php

namespace App\Http\Livewire\Auth;

use App\Mail\CodeVerifyMail;
use App\Models\Company;
use App\Models\Country;
use App\Models\User;
use App\Notifications\SendMessageNotification;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Illuminate\Support\Facades\Mail;
use Livewire\WithFileUploads;
use Doinc\PersonaKyc\Persona;

class Register extends Component
{
    use WithFileUploads;

    public $current_step = 1;
    public $email;
    public $otp, $user_otp;
    public $new_password, $verify_password;
    public $full_name, $phone_prefix, $phone, $id_scan;
    public $company_name, $registration_id, $field_of_business, $incorporation_document;
    public $countries, $street, $city, $country, $company_street, $company_city, $company_country;
    public $success = null, $error = null, $documentUploadMsg = null;
    protected $listeners = [];
    public $code0 = null, $code1 = null, $code2 = null, $code3 = null;

    /**
     * Create a user register
     *
     * @return response()
     */
    public function render()
    {
        $this->documentUploadMsg = "Document uploaded successfully";
        $this->countries = Country::select('*')
            ->where('is_active', 1)
            ->get();
        return view('livewire.auth.register')->layout('layouts.guest');
    }

    /**
     * Create first step for registration
     *
     * @return response()
     */
    public function firstStepSubmit()
    {
        $this->validate([
            'email' => 'required|email:rfc,dns|unique:users',
        ]);

        $this->current_step = 2;
        $this->sendOtpMail();
    }

    /**
     * check the submitted otp
     *
     * @param int $index
     *
     * @author Moh Ashraf
     */
    public function checkCodeLength()
    {
        if ( $this->code0 != null && $this->code1 != null && $this->code2 != null && $this->code3 != null) {
            $this->secondStepSubmit();
        }
    }

    /**
     * Create second step for registration
     *
     * @return response()
     */
    public function secondStepSubmit()
    {
        $this->clear();
        $this->user_otp = $this->code0.$this->code1.$this->code2.$this->code3;
        $this->validate([
            'user_otp' => 'required|in:' . $this->otp,
        ]);
        $this->current_step = 3;
    }

    /**
     * Create third step for registration
     *
     * @return response()
     */
    public function thirdStepSubmit()
    {
        $this->validate([
            'new_password'    => 'required|min:8',
            'verify_password' => 'required|same:new_password',
        ]);

        $this->current_step = 4;
    }

    /**
     * Create fourth step for registration
     *
     * @return response()
     */
    public function fourthStepSubmit()
    {
        $this->validate([
            'full_name'    => 'required',
            'phone_prefix' => 'required',
            'phone'        => 'required|numeric',
            'id_scan'      => 'nullable|image|max:5120',
        ]);

        $this->current_step = 5;
    }

    /**
     * Create fifth step for registration
     *
     * @return response()
     */
    public function fifthStepSubmit()
    {
        $this->validate([
            'company_name'           => 'nullable',
            'registration_id'        => 'nullable',
            'field_of_business'      => 'nullable',
            'incorporation_document' => 'nullable|image|max:5120',
        ]);

        $this->current_step = 6;
    }

    /**
     * Create sixth step for registration
     *
     * @return response()
     */
    public function sixthStepSubmit()
    {
        $this->validate([
            'street'          => 'required',
            'city'            => 'required',
            'country'         => 'required|not_in:0',
            'company_street'  => 'nullable',
            'company_city'    => 'nullable',
            'company_country' => 'nullable',
        ]);

        $this->submitForm();
    }

    /**
     * Create skip step for registration
     *
     * @return response()
     */
    public function skipFifthStep()
    {
        $this->company_name = '';
        $this->registration_id = '';
        $this->field_of_business = '';
        $this->incorporation_document = '';
        $this->current_step = 6;
    }

    /**
     * Register user
     *
     * @return response()
     */
    public function submitForm()
    {
        $idScanName = null;
        if ( $this->id_scan ) {
            $full_name = $this->id_scan->getClientOriginalName();
            $ext = pathinfo($full_name, PATHINFO_EXTENSION);
            $filename = 'id_proof' . time() . '.' . $ext;
            $path = 'images/';
            $this->id_scan->storeAs($path, $filename, 'public');
            $idScanName = $path . '/' . $filename;
        }
        $user = [
            'email'        => $this->email,
            'password'     => Hash::make($this->verify_password),
            'name'         => $this->full_name,
            'phone_prefix' => $this->phone_prefix,
            'phone'        => $this->phone,
            'id_proof'     => $idScanName,
            'status'       => false,
            'street'       => $this->street,
            'city'         => $this->city,
            'country_id'   => $this->country,
        ];

        $user = User::create($user)->assignRole(ROLE_USER);
        // $persona_account = Persona::init()->accounts()->create($user->id);
        // if($persona_account){
        //     $user->persona_account_id   =   $persona_account->id;
        //     $user->save();
        // }
        $incorporationDocumentName = null;
        if ( $this->incorporation_document ) {
            $full_name = $this->incorporation_document->getClientOriginalName();
            $ext = pathinfo($full_name, PATHINFO_EXTENSION);
            $filename = 'incorporation_document' . time() . '.' . $ext;
            $path = 'images/';
            $this->incorporation_document->storeAs($path, $filename, 'public');
            $incorporationDocumentName = $path . '/' . $filename;
        }

        if ( !empty($this->company_name) || !empty($this->field_of_business) || !empty($this->company_street) || !empty($this->company_city) || !empty($this->company_country) || !empty($this->registration_id) || !empty($incorporationDocumentName) ) {
            Company::create([
                'user_id'               => $user->id,
                'name'                  => $this->company_name,
                'field'                 => $this->field_of_business,
                'street'                => $this->company_street,
                'city'                  => $this->company_city,
                'country_id'            => $this->company_country,
                'registration_id'       => $this->registration_id,
                'incorporation_doc_url' => $incorporationDocumentName,
            ]);
        }

        $msg = 'Thank you for sign up on ' . env('app_name') . '!';
        $message = 'New User Registered:<a href="'.route('admin.users').'">'.  $user->name. '</a>';
        $admin_user = User::find(1);
        $admin_user->notify(new SendMessageNotification($message,'',$user));
        activity()
            ->performedOn($user)
            ->causedBy($user)
            ->log('Registered as <b>:subject.name</b> with email <b>:subject.email</b> has been');
        $this->clearForm();

        session()->flash('success', $msg);
        return redirect()->to('register');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function clearForm()
    {
        $this->email = '';
        $this->code0 = '';
        $this->code1 = '';
        $this->code2 = '';
        $this->code3 = '';
        $this->otp = '';
        $this->new_password = '';
        $this->verify_password = '';
        $this->full_name = '';
        $this->phone = '';
        $this->phone_prefix = '';
        $this->id_scan = '';
        $this->company_name = '';
        $this->registration_id = '';
        $this->field_of_business = '';
        $this->incorporation_document = '';
        $this->countries = '';
        $this->street = '';
        $this->city = '';
        $this->country = '';
        $this->company_street = '';
        $this->company_city = '';
        $this->company_country = '';
        $this->success = null;
    }

    public function sendOtpMail($send = false)
    {
        $this->clear();
        $otp = rand(1000, 9999);//rand(1000, 9999);
        $this->otp = $otp;
        $details = [
            "otp" => $otp,
        ];


        try {
            Mail::to($this->email)->send(new CodeVerifyMail($details));
            $this->success = 'OTP sent on yours email successfully';
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }
    }

    public function redirecToSocialLogin($type = 'google')
    {
        return redirect()->route('login.social', ['loginType' => $type]);
    }

    public function redirectToSignIn()
    {
        return redirect()->route('login');
    }

    /**
     * @author Moh Ashraf
     */
    public function takeOneStepBack()
    {
        $this->current_step = $this->current_step - 1;
        if ( $this->current_step == 2 ) {
            $this->user_otp = null;
            $this->code0 = null;
            $this->code1 = null;
            $this->code2 = null;
            $this->code3 = null;
        }
    }

    public function clear()
    {
        $this->success = null;
        $this->error = null;
    }
}
