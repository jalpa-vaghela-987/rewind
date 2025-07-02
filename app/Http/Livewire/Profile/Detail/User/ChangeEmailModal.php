<?php

namespace App\Http\Livewire\Profile\Detail\User;

use App\Models\User;
use App\Models\UserToken;
use App\Notifications\SendMessageNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Component;

class ChangeEmailModal extends Component
{
    public $email;
    public $new_email;
    public $showModal = false;
    protected $listeners = ['openCloseChangeEmailModal' => 'openCloseChangeEmailModal'];

    protected function rules()
    {
        return [
            'new_email' => ['required', 'email', 'different:email', 'unique:users,email', 'unique:users,new_email,' . auth()->user()->id],
        ];
    }

    protected function messages()
    {
        return [
            'new_email.different' => 'The new email must be different to the old one',
            'new_email.unique'    => 'The new email is already exist',
        ];
    }

    public function render()
    {
        return view('livewire.profile.detail.user.change-email-modal');
    }

    public function openCloseChangeEmailModal()
    {
        $this->showModal = true;
    }

    public function closeChangeEmailModal()
    {
        $this->showModal = false;
    }

    public function sendValidationCode()
    {
        $this->validate();
        /**For Mobile */
        $token = rand(1000, 9999);
        /**For Mobile */
        $rand_str = Str::random(30);
        $verification_url = route('verifyEmail', ['validate_str' => $rand_str]);
        $details['title'] = "Please verify your email";
        $details['url'] = $verification_url;
        $details['body'] = 'Please click the below link(for web) or use otp(for app) to verify this as your new email for ' . config('app.name') . ' login.';
        $details['token'] = $token;
        /** */
        // Mailgun
        /** */
        Mail::to($this->new_email)->send(new \App\Mail\EmailVerifyMail($details));
        User::where('email', $this->email)->update([
            'new_email'                 => $this->new_email,
            'email_verified'            => false,
            'email_verification_string' => $rand_str,
        ]);
        $user = User::where('email', $this->email)->first();
        /**For mobile */
        UserToken::updateOrInsert(['user_id' => $user->id],
            [
                'token'      => $token,
                'created_at' => Carbon::now(),
                'type'       => 'new_email',
            ]);
        /**For mobile */
        session()->flash('success', 'Verification email with OTP sent successfully!');
        activity()
            ->performedOn($user)
            ->causedBy(auth()->user())
            ->log('verification email on <b>:subject.new_email</b> has been Sent');
        $message = 'User Email Changed:<a href="'.route('admin.users').'">'.  $this->user->name. '</a>';
        $user = User::find(1);
        $user->notify(new SendMessageNotification($message));
        $this->emit('reRenderParent');
        $this->emitSelf('$refresh');
        $this->closeChangeEmailModal();
    }
}
