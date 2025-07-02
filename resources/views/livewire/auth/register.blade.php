<div>
    <div class="container-fluid min-vh-100">
        <div class="wrapper d-flex align-items-stretch flex-column flex-md-row min-vh-100">
            <div class="row ms-auto bg-white right-content min-vh-100 d-flex justify-content-center">
                <div class="sign-up-block col-auto">
                    <div class="row mb-70">
                        <img src="{{asset('img/logo.png')}}" class="logo icon-logo-green col-auto p-0 w-75" alt="Logo"/>
                    </div>
                    <nav class="breadcrumb-ellipse row d-flex align-items-center">
                        <a href="javascript:void(0);" class="link col-auto base-link nav-prev {{$current_step}} {{$current_step==1?'invisible':''}}"  wire:click.prevent="takeOneStepBack">
                            <svg class="icon icon-Arrow" width="24" height="24">
                                <use href="{{asset('img/icons.svg#icon-Arrow')}}"></use>
                            </svg>
                        </a>
                        <div class="ellipse ms-auto col-auto" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link {{ $current_step == 1 ? 'active' : 'disabled' }}" id="nav-email-tab" data-bs-target="#nav-email"
                               type="button" role="tab" aria-controls="nav-email" aria-selected="true"
                               href="#"><span>Step 1</span>
                            </a>
                            <a class="nav-item nav-link {{ $current_step == 2 ? 'active' : 'disabled' }}" id="nav-code-tab" data-bs-target="#nav-code" type="button"
                               role="tab" aria-controls="nav-code" aria-selected="false" href="#"><span>Step
                                    2</span></a>
                            <a class="nav-item nav-link {{ $current_step == 3 ? 'active' : 'disabled' }}" id="nav-pass-tab" data-bs-target="#nav-pass" type="button"
                               role="tab" aria-controls="nav-password" aria-selected="false" href="#"><span>Step
                                    3</span></a>
                            <a class="nav-item nav-link {{ $current_step == 4 ? 'active' : 'disabled' }}" id="nav-details-tab" data-bs-target="#nav-details"
                               type="button" role="tab" aria-controls="nav-details1" aria-selected="false"
                               href="#"><span>Step 4</span></a>
                            <a class="nav-item nav-link {{ $current_step == 5 ? 'active' : 'disabled' }}" id="nav-company-tab" data-bs-target="#nav-company"
                               type="button" role="tab" aria-controls="nav-company1" aria-selected="false"
                               href="#"><span>Step
                                    5</span></a>
                            <a class="nav-item nav-link {{ $current_step == 6 ? 'active' : 'disabled' }}" id="nav-address-tab" data-bs-target="#nav-address"
                               type="button" role="tab" aria-controls="nav-address" aria-selected="false"
                               href="#"><span>Step 6</span></a>
                        </div>
                    </nav>
                    <div class="tab-content row" id="nav-tabContent">
                        <div class="tab-pane {{ $current_step == 1 ? 'show active' : '' }}" id="nav-email" role="tabpanel"
                             aria-labelledby="nav-email-tab">
                            <h4 class="fw-bold  mt-65 mb-40 row">Sign Up</h4>
                            <div class="row justify-content-around gap-2 gap-sm-0 justify-content-sm-between mb-40">
                                <button class="social-buttons w-auto" wire:click.prevent="redirecToSocialLogin('google')">
                                    <svg class="icon icon-Microsoft" width="24" height="24">
                                        <use href="{{asset('img/icons.svg#icon-Google')}}"></use>
                                    </svg>
                                    Google</button>
                                    <button class="social-buttons w-auto" wire:click.prevent="redirecToSocialLogin('apple')">
                                        <svg class="icon icon-Microsoft"
                                                                                        width="24" height="24">
                                        <use href="{{asset('img/icons.svg#icon-Apple')}}"></use>
                                    </svg>Apple ID</button>
                                    <button class="social-buttons w-auto " wire:click.prevent="redirecToSocialLogin('azure')"><svg
                                        class="icon icon-Microsoft" width="24" height="24">
                                        <use href="{{asset('img/icons.svg#icon-Microsoft')}}"></use>
                                    </svg>Microsoft</button>
                            </div>
                            <form method="post" class="signup" id="signup-step1"  wire:submit.prevent="firstStepSubmit">
                                @csrf
                                <div class="row  mb-40">
                                    <p class="title text-center"><span class="py-1 bg-white ">Or Continue
                                            With</span>
                                    </p>
                                </div>

                                <div class="row mb-24">
                                    <label for="email" class="form-label p-0 black-color">Email</label>
                                    <input type="text" wire:model="email" class="form-control default">
                                    <x-jet-input-error for="email" class="mt-2" />
                                </div>
                                <div class="row mb-30">
                                    <button type="submit" class="button-green  w-100  button-next" {{($email == null) ? 'disabled' : ''}}>Next</button>
                                </div>
                                <div class="row d-flex justify-content-between align-items-center">
                                    <a href="{{route('login')}}" class="link base-link col-auto fw-bold dark">Already Have An Account?</a>
                                    <button type="button" class="button-secondary button-login col-auto" wire:click.prevent="redirectToSignIn">Log In</button>
                                </div>

                            </form>
                        </div>
                        <div class="tab-pane fade {{ $current_step == 2 ? 'show active' : '' }}" id="nav-code" role="tabpanel" aria-labelledby="nav-code-tab">
                            <h4 class="fw-bold mt-40 mb-30 verification-text row">Enter the verification code we’ve
                                sent to your email.</h4>
                            @if($success != null)
                                <p class="row mb-3 p-0 font-medium text-sm text-green-600">{{$success}}</p>
                            @endif
                            @if($error != null)
                                <p class="row mb-3 p-0 font-medium text-sm text-red-600">{{$error}}</p>
                            @endif
                            <form method="post" class="signup" id="signup-step2" wire:submit.prevent="secondStepSubmit">
                                @csrf
                                <div class="row justify-content-between mb-40">
                                    <input
                                        type="number" wire:model.defer="code0" maxlength="1" class="form-control default text-code otp-input {{$errors->has('user_otp')?'error':''}}">
                                    <input
                                        type="number" wire:model.defer="code1" maxlength="1" class="form-control default text-code otp-input {{$errors->has('user_otp')?'error':''}}">
                                    <input
                                        type="number" wire:model.defer="code2" maxlength="1" class="form-control default text-code otp-input {{$errors->has('user_otp')?'error':''}}">
                                    <input
                                        type="number" wire:model.defer="code3" maxlength="1" class="form-control default text-code otp-input {{$errors->has('user_otp')?'error':''}}" wire:keyup="checkCodeLength">
                                    <x-jet-input-error for="user_otp" class="mt-2" />
                                </div>
                                <div
                                    class="row d-flex justify-content-around justify-content-sm-between align-items-center gap-2 gap-sm-0">
                                    <a href="#" class="link base-link col-auto fw-bold dark">Haven’t received a code yet?</a>
                                    <button type="button" class="button-secondary button-login col-auto" wire:click.prevent="sendOtpMail(true)">Send A New
                                        Code</button>
                                </div>

                            </form>
                        </div>
                        <div class="tab-pane fade {{ $current_step == 3 ? 'show active' : '' }}" id="nav-password" role="tabpanel" aria-labelledby="nav-pass-tab">
                            <h4 class="fw-bold mt-65 mb-30 verification-text row">Choose Your Password</h4>
                            <form method="post" class="signup" id="signup-step3">
                                @csrf
                                <div class="row mb-24">
                                    <label for="password" class="form-label p-0 black-color">Password</label>
                                    <div class="position-relative p-0">
                                        <input type="password"  wire:model="new_password" class="form-control default" id="password"
                                               placeholder=" "><span toggle="#password" class="field-icon toggle-password">
                                            <svg class="icon icon-Eye" width="24" height="24">
                                                <use href="{{asset('img/icons.svg#icon-Eye')}}"></use>
                                            </svg>
                                            <svg class="icon icon-Eye-off" width="24" height="24">
                                                <use href="{{asset('img/icons.svg#icon-Eye-off')}}"></use>
                                            </svg>
                                        </span>
                                    </div>
                                    <x-jet-input-error for="new_password" class="mt-2" />
                                </div>
                                <div class="row mb-24">
                                    <label for="password2"  class="form-label p-0 black-color">Verify Password</label>
                                    <div class="position-relative p-0">
                                        <input type="password" wire:model="verify_password" class="form-control default" id="password2"
                                               placeholder=" ">
                                        <span toggle="#password2" class="field-icon toggle-password"><svg
                                                class="icon icon-Eye" width="24" height="24">
                                                <use href="{{asset('img/icons.svg#icon-Eye')}}"></use>
                                            </svg>
                                            <svg class="icon icon-Eye-off" width="24" height="24">
                                                <use href="{{asset('img/icons.svg#icon-Eye-off')}}"></use>
                                            </svg>
                                        </span>
                                    </div>
                                    <x-jet-input-error for="verify_password" class="mt-2" />
                                </div>
                                <div class="row mb-30">
                                    <button type="button" class="button-green  w-100  button-next" wire:click="thirdStepSubmit" {{($new_password == null || $verify_password == null) ? 'disabled' : ''}}>Next</button>
                                </div>
                                <div class="row d-flex justify-content-between align-items-center">
                                    <a href="{{route('login')}}" class="link base-link col-auto fw-bold dark">Already Have An Account?</a>
                                    <button type="button" class="button-secondary button-login col-auto" wire:click.prevent="redirectToSignIn">Log In</button>
                                </div>

                            </form>
                        </div>
                        <div class="tab-pane fade {{ $current_step == 4 ? 'show active' : '' }}" id="nav-details1" role="tabpanel" aria-labelledby="nav-details-tab">
                            <h4 class="fw-bold mt-65 mb-30 verification-text row">Enter your details</h4>
                            <form method="post" wire:submit.prevent="submit" class="signup" id="signup-step4" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-24">
                                    <label for="full_name" class="form-label p-0 black-color">Full Name</label>
                                    <input type="text" wire:model="full_name"  class="form-control default {{$errors->has('full_name')?'error':''}}" id="full_name">
                                    <x-jet-input-error for="full_name" class="mt-2" />
                                </div>
                                <div wire:ignore class="row mb-24">
                                    <label for="phone" class="form-label p-0 black-color">Phone Number</label>
                                    <select class="form-control default w-25 me-2 {{$errors->has('phone_prefix')?'error':''}} selectpicker"  id="phone_prefix" title=" "  wire:model="phone_prefix" data-live-search="true">
                                        <option></option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->phone_prefix }}">+{{ $country->phone_prefix }}</option>
                                        @endforeach
                                    </select>
                                    <input type="number" wire:model="phone"  class="form-control default col {{$errors->has('phone')?'error':''}}" id="phone">
                                    <x-jet-input-error for="phone_prefix" class="mt-2" />
                                    <x-jet-input-error for="phone" class="mt-2" />
                                </div>
                                <div class="row mb-40">
                                    <label for="fileElem" class="form-label p-0 black-color">ID Scan</label>
                                    <label class="drop-area {{($id_scan) ? 'full' : ''}}">
                                        <span><span class="info">
                                                <svg class="icon icon-upload-cloud" width="32" height="32">
                                                    <use href="{{asset('img/icons.svg#icon-upload-cloud')}}"></use>
                                                </svg>
                                                <b>upload a file </b>or drag and
                                                drop</span>
                                            <span class="limit">PNG, JPG, GIF up to 10MB</span>
                                        </span>
                                        <input type="file" wire:model="id_scan" id="corporateDoc" class="fileElem {{$errors->has('id_scan')?'error':''}}" accept="image/*" style="display:none;">
                                        @if($id_scan)
                                            <span class="gallery">
                                                <img src="{{$id_scan->temporaryUrl()}}"/>
                                            </span>
                                        @endif
                                    </label>
                                    @if($id_scan)
                                        <p class="mt-2 p-0 font-medium text-sm text-green-600">{{$documentUploadMsg}}</p>
                                    @endif
                                </div>
                                <x-jet-input-error for="id_scan" class="mt-2" />
                                <div class="row mb-30">
                                    <button type="button" class="button-green  w-100  button-next" wire:click="fourthStepSubmit" {{($full_name == null || $phone == null || $id_scan == null || $phone_prefix == null) ? 'disabled' : ''}}>Next</button>
                                </div>
                                <div class="row d-flex justify-content-between align-items-center">
                                    <a href="{{route('login')}}" class="link base-link col-auto fw-bold dark">Already Have An Account?</a>
                                    <button type="button" class="button-secondary button-login col-auto" wire:click.prevent="redirectToSignIn">Log
                                        In</button>
                                </div>

                            </form>
                        </div>

                        <div class="tab-pane fade {{ $current_step == 5 ? 'show active' : '' }}" id="nav-company1" role="tabpanel" aria-labelledby="nav-company-tab">
                            <h4 class="fw-bold mt-65 mb-30 verification-text row">Company Details</h4>
                            <form method="post" class="signup" id="signup-step5" enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-24">
                                    <label for="company_name" class="form-label p-0 black-color">Company Name
                                        (Optional)</label>
                                    <input type="text" wire:model="company_name" class="form-control default" id="company_name">
                                </div>
                                <div class="row mb-24">
                                    <label for="registration_id" class="form-label p-0 black-color">Registration
                                        ID</label>
                                    <input type="number" wire:model="registration_id" class="form-control default" id="registration_id">
                                </div>
                                <div class="row mb-24">
                                    <label for="fieldBus" class="form-label p-0 black-color">Field Of Business</label>
                                    <input type="text" wire:model="field_of_business" class="form-control default" id="fieldBus">
                                </div>
                                <div class="row mb-24">
                                        <label for="fileElem" class="form-label p-0 black-color">Upload Incorporation
                                        Documents</label>
                                    <label class="drop-area {{($incorporation_document) ? 'full' : ''}}">
                                        <span><span class="info">
                                                <svg class="icon icon-upload-cloud" width="32" height="32">
                                                    <use href="{{asset('img/icons.svg#icon-upload-cloud')}}"></use>
                                                </svg>
                                                <b>upload a file </b>or drag and
                                                drop</span>
                                            <span class="limit">PNG, JPG, GIF up to 10MB</span>
                                        </span>
                                        <input type="file" wire:model="incorporation_document" class="fileElem" id="fileElem" accept="image/*">
                                        <span class="gallery">
                                            @if($incorporation_document)
                                                <img src="{{$incorporation_document->temporaryUrl()}}"/>
                                            @endif
                                        </span>
                                    </label>
                                    @if($incorporation_document)
                                        <p class="mt-2 p-0 font-medium text-sm text-green-600">{{$documentUploadMsg}}</p>
                                    @endif
                                </div>
                                <div class="row mb-24 text-center ">
                                    <a href="#" class="link text-black" wire:click="skipFifthStep">Skip This Step</a>
                                </div>
                                <div class="row mb-30">
                                    <button type="button" class="button-green  w-100  button-next" wire:click="fifthStepSubmit" {{($registration_id == null || $field_of_business == null || $incorporation_document == null) ? 'disabled' : ''}}>Next</button>
                                </div>
                                <div class="row d-flex justify-content-between align-items-center">
                                    <a href="{{route('login')}}" class="link base-link col-auto fw-bold dark">Already Have An Account?</a>
                                    <button type="button" class="button-secondary button-login col-auto" wire:click.prevent="redirectToSignIn">Log
                                        In</button>
                                </div>

                            </form>
                        </div>

                        <div class="tab-pane fade {{ $current_step == 6 ? 'show active' : '' }}" id="nav-address" role="tabpanel" aria-labelledby="nav-address-tab">
                            <h4 class="fw-bold mt-65 mb-30 verification-text row">Enter Your Address</h4>
                            <form method="post" class="signup" id="signup-step6">
                                @csrf
                                <div class="row mb-24">
                                    <label for="street" class="form-label p-0 black-color">Street</label>
                                    <input type="text" wire:model="street" class="form-control default {{$errors->has('street')?'error':''}}" id="street">
                                    <x-jet-input-error for="street" class="mt-2" />
                                </div>
                                <div class="row mb-24 gap-3">
                                    <div class="col w-50 row">
                                        <label for="City" class="form-label p-0 black-color">City</label>
                                        <input type="text" wire:model="city" class="form-control default {{$errors->has('city')?'error':''}}" id="user_city">
                                        <x-jet-input-error for="city" class="mt-2" />
                                    </div>
                                        <div class="col w-50 row">
                                            <span wire:ignore class="m-0 p-0">
                                                <label for="Country" class="form-label p-0 black-color">Country</label>
                                                <select class="form-control default {{$errors->has('country')?'error':''}} selectpicker" id="country" title=" "  wire:model="country" data-live-search="true">
                                                    <option value="0"></option>
                                                    @foreach($countries as $country)
                                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </span>
                                        <x-jet-input-error for="country" class="mt-2" />
                                    </div>
                                </div>
                                <h4 class="fw-bold mb-24 verification-text row">Company Address (Optional)</h4>
                                <div class="row mb-24">
                                    <label for="company_street" class="form-label p-0 black-color">Street</label>
                                    <input type="text" wire:model="company_street" class="form-control default {{$errors->has('company_street')?'error':''}}" id="company_street">
                                    <x-jet-input-error for="company_street" class="mt-2" />
                                </div>
                                <div class="row mb-24 gap-3">
                                    <div class="col w-50 row"><label for="City"
                                                                class="form-label p-0 black-color">City</label>
                                        <input type="text" wire:model="company_city"  class="form-control default {{$errors->has('company_city')?'error':''}}" id="company_city">
                                        <x-jet-input-error for="company_city" class="mt-2" />
                                    </div>
                                    <div wire:ignore class="col w-50 row"><label for="Country"
                                                                class="form-label p-0 black-color">Country</label>
                                            <select class="form-control default selectpicker" id="company_country" title=" "  wire:model="company_country" data-live-search="true">
                                                <option value=""></option>
                                                @foreach($countries as $country)
                                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                            <x-jet-input-error for="company_country" class="mt-2" />
                                    </div>
                                </div>
                                <div class="row mb-30">
                                    <button type="button" class="button-green  w-100  button-next" wire:click="sixthStepSubmit" {{($street == null || $city == null || $country == null) ? 'disabled' : ''}}>Next</button>
                                </div>
                                <div class="row d-flex justify-content-between align-items-center">
                                    <a href="{{route('login')}}" class="link base-link col-auto fw-bold dark">Already Have An Account?</a>
                                    <button type="button" class="button-secondary button-login col-auto" wire:click.prevent="redirectToSignIn">Log
                                        In</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
