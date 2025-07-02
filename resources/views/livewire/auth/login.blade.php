<div>
    <div class="container-fluid min-vh-100">
        <div class="wrapper d-flex align-items-stretch flex-column flex-md-row min-vh-100">
            <div class="row ms-auto bg-white right-content min-vh-100 d-flex justify-content-center">
                <div class="login-block col-auto">
                    <div class="row mb-70">
                        <img src="{{asset('img/logo.png')}}" class="logo icon-logo-green col-auto p-0 w-75" alt="Logo"/>
                    </div>
                    <h4 class="fw-bold  mt-65 mb-40 row">Log In</h4>
                    @if (session('status'))
                        <div class="mb-4 font-medium text-sm text-green-600">
                            {{ session('status') }}
                        </div>
                    @endif
                        <div class="row justify-content-around gap-2 gap-sm-0 justify-content-sm-between mb-40">
                            <button class="social-buttons w-auto" wire:click.prevent="redirecToSocialLogin('google')">
                                <svg class="icon icon-Microsoft" width="24" height="24">
                                    <use href="{{asset('img/icons.svg#icon-Google')}}"></use>
                                </svg>
                                Google</button>
                            <button class="social-buttons w-auto" wire:click.prevent="redirecToSocialLogin('apple')"><svg class="icon icon-Microsoft"
                                    width="24" height="24">
                                    <use href="{{asset('img/icons.svg#icon-Apple')}}"></use>
                                </svg>Apple ID</button>
                            <button class="social-buttons w-auto " wire:click.prevent="redirecToSocialLogin('azure')"><svg
                                    class="icon icon-Microsoft" width="24" height="24">
                                    <use href="{{asset('img/icons.svg#icon-Microsoft')}}"></use>
                                </svg>Microsoft</button>
                        </div>
                        <div class="row  mb-40">
                            <p class="title text-center"><span class="py-1 bg-white ">OR</span>
                            </p>
                        </div>
                        <form method="POST" wire:submit.prevent="loginUser">
                        @csrf
                        <x-jet-validation-errors class="mb-4" />
                        <div class="row mb-24">
                            <label for="email" class="form-label p-0 black-color">{{ __('Email') }}</label>
                            <input type="text" class="form-control default" id="email" type="email" wire:model="email" required autofocus>
                        </div>
                        <div class="row mb-24">
                            <label for="password" class="form-label p-0 black-color">{{ __('Password') }}</label>
                            <div class="position-relative p-0">
                                <input type="password" class="form-control default" id="password" placeholder=" " wire:model="password" required autocomplete="current-password" /><span
                                    toggle="#password" class="field-icon toggle-password">
                                    <svg class="icon icon-Eye" width="24" height="24">
                                        <use href="{{asset('img/icons.svg#icon-Eye')}}"></use>
                                    </svg>
                                    <svg class="icon icon-Eye-off" width="24" height="24">
                                        <use href="{{asset('img/icons.svg#icon-Eye-off')}}"></use>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="row mb-24">
                            <div class="remember-input-group col-auto p-0">
                                <input type="checkbox" name="remember" id="remember" wire:model.defer="remember" class="visually-hidden">
                                <label for="remember" class="remember-label d-flex align-items-center">
                                    <span class=" remember-check d-flex justify-content-center align-items-center">
                                        <svg width="12" height="8.67">
                                            <use href="{{asset('img/icons.svg#icon-Check')}}"></use>
                                        </svg>
                                    </span>
                                    {{ __('Remember me') }}</label>
                            </div>
                            <div class="col-auto ms-auto">
                                <a href="#" class="reset-pass" wire:click.prevent="$emit('openCloseForgotPasswordModal')">Forgot Your Password?</a>
                            </div>
                        </div>
                        <div class="row mb-30">
                            <button type="submit" class="button-green  w-100" {{($email == null || $password == null) ? 'disabled' : ''}}>{{ __('Log in') }}</button>
                        </div>
                        <div class="row d-flex justify-content-between align-items-center">
                            <a href="{{route('register')}}" class="link base-link fw-bold dark col-auto">Don't Have An Account?</a>
                            <button type="button" class="button-secondary button-login col-auto" wire:click.prevent="redirectToSignUp">Sign Up</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('modals')
        @livewire("auth.forgot-password-modal")
        @livewire("auth.verify-forgot-password-modal", ['showModal' => $verifyPasswordModal, 'token' => $token])
        @livewire("auth.forgot-password-changed-modal", ['showModal' => !empty(session('changedPassword')) ? true : false])
    @endpush
</div>
