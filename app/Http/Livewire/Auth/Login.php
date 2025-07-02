<?php

namespace App\Http\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class Login extends Component
{
    public $email, $password, $remember = false, $loginType = "normal";
    public $verifyPasswordModal = false, $token;
    public $forgotPasswordChangedModal = false;
    protected $listeners = ['reRenderParent'];

    /**
     * rendering login screen
     */
    public function render()
    {
        session()->forget('key');
        return view('livewire.auth.login')->layout('layouts.guest');
    }

    public function mount($token = null)
    {
        $this->getBrowserCookie();
        if ( $token != null ) {
            $this->token = $token;
            $this->verifyPasswordModal = true;
        }
        $this->render();
    }

    /**
     * resetting inputs
     */
    private function resetInputFields()
    {
        $this->email = '';
        $this->password = '';
    }

    /**
     * login
     */
    public function loginUser()
    {
        $validated = $this->validate([
            'email'    => 'required|email|exists:users,email',
            'password' => 'required|min:6|max:16',
        ]);
        $record = User::where('email', $this->email)->first();
        if ( $record->status == 0 ) {
            throw ValidationException::withMessages([
                'email' => [trans('Account status pending approval!')],
            ]);
        }

        if ( Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember) ) {
            if($this->remember && $this->remember == 1){
                setcookie('email', $this->email, time()+3600, "/login");
                setcookie('password', $this->password, time()+3600, "/login");
            } else {
                setcookie("email", "",  time()+3600, "/login");
                setcookie("password", "",  time()+3600, "/login");
            }
            session()->flash('success', 'You have been successfully login.');
        } else {
            throw ValidationException::withMessages([
                'email' => [trans('Invalid login credentials...')],
            ]);
        }
        activity()
            ->performedOn($record)
            ->causedBy(auth()->user())
            // ->withProperties(['laravel' => 'awesome'])
            ->log('logged in as :causer.name');

        if ( auth()->user()->hasRole('admin') && auth()->user()->email == 'admin@gmail.com' ) {
            return redirect()->to('admin/dashboard');
        } else {
            return redirect()->to('dashboard');
        }


    }

    public function redirectToSignUp()
    {
        return redirect()->route('register');
    }

    public function redirecToSocialLogin($type = 'google')
    {
        return redirect()->route('login.social', ['loginType' => $type]);
    }

    public function showForgotPasswordChangedModal()
    {
        $this->forgotPasswordChangedModal = true;
        $this->render();
    }

    public function reRenderParent()
    {
        $this->mount();
        $this->render();
    }

    public function getBrowserCookie() {
        if(isset($_COOKIE['email']) && isset($_COOKIE['password'])){
            $this->email = $_COOKIE['email'];
            $this->password = $_COOKIE['password'];
            $this->remember = true;
        }
    }
}
