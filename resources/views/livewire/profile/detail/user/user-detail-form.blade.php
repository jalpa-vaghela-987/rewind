<div>
    <div class="col-12 col-lg row p-3 w-525" id="details-user">
        <div class="row col-12 mb-24">
            <p class="fw-bold fs-16">Profile Picture</p>
        </div>
        <form method="POST" wire:submit.prevent enctype="multipart/form-data">
            <div class="row col-12 mb-24">
                @if(!empty($user->profile_photo_path))
                <div class="col-6 image d-flex align-items-center">
                    <div class="box image rounded-circle">
                        <img src="{{ $user->profile_photo_path.'?'.time() }}" alt="User Image">
                    </div>
                </div>
                @endif
                <div class="col-6 d-flex align-items-center justify-content-end">
                    <button type="button" class="btn button-secondary user-detail-btn" wire:click.prevent="$emit('openCloseProfilePictureModal')">Change Picture</button>
                    @push('modals')
                        @livewire('profile.detail.user.profile-picture-upload-form')
                    @endpush
                </div>
            </div>
            <div class="row col-12 mb-24">
                <div class="col-12">
                    <hr class="black-10 my-0">
                </div>
            </div>
            <div class="row col-12 mb-24">
                <div class="col-6">
                    <p class="col fw-bold fs-16 mb-3">Name</p>

                    @if($disabled["name"])
                        <p class="col text-black-50 fs-16">{{$user->name}}</p>
                    @else
                        <input id="name"
                            type="text"
                            class="default col text-black-50 fs-16"
                            wire:model.defer="user.name"
                            autocomplete="name"
                        />
                    @endif
                    <x-jet-input-error for="user.name" class="mt-2" />
                </div>
                <div class="col-6 d-flex align-items-center justify-content-end">
                    @if($disabled["name"])
                        <button
                            type="button"
                            class="btn button-secondary edit-input px-4 user-detail-btn"
                            wire:click.prevent="makeEditable('name')">
                            Change Name
                        </button>
                        @else
                        <button
                            type="button"
                            class="btn button-green edit-input px-4"
                            wire:click.prevent="saveName()">
                            Save
                        </button>
                    @endif
                </div>
            </div>
            <div class="row col-12 mb-24">
                <div class="col-12">
                    <hr class="black-10 my-0">
                </div>
            </div>
            <div class="row col-12 mb-24">
                <div class="col-6">
                    <p class="col fw-bold fs-16 mb-3">{{ __('Email') }}</p>
                    <p class="col text-black-50 fs-16">{{$user->email}}</p>
                </div>
                <div class="col-6 d-flex align-items-center justify-content-end">
                @if($user->email_verified)
                    <button
                        type="button"
                        class="btn button-secondary user-detail-btn"
                        wire:click.prevent="$emit('openCloseChangeEmailModal')">Change Email
                    </button>
                    @push('modals')
                    @livewire('profile.detail.user.change-email-modal',['email'=>$user->email])
                    @endpush
                @else
                    <button type="button"
                        class="btn status-button pending fs-16 fw-normal button-secondary"
                        wire:click.prevent="$emit('openCloseResendVerificationEmail')"
                    ><svg class="icon icon-alert-circle me-2"
                                width="16"
                                height="16">
                                <use href="{{asset('img/icons.svg#icon-alert-circle')}}"></use>
                        </svg>Pending Validation
                    </button>
                    @push('modals')
                    @livewire("profile.detail.user.resend-verification-email-modal",['email'=>$user->email])
                    @endpush
                @endif
                </div>
            </div>
            <div class="row col-12 mb-24">
                <div class="col-12">
                    <hr class="black-10 my-0">
                </div>
            </div>
            <div class="row col-12 mb-24">
                <div class="col-6">
                    <p class="col fw-bold fs-16 mb-3">{{ __('Address') }}</p>
                    <p class="col text-black-50 fs-16">{{$address}}</p>
                </div>
                <div class="col-6 d-flex align-items-center justify-content-end">
                    <button
                        type="button"
                        class="btn button-secondary user-detail-btn"
                        wire:click.prevent="$emit('openCloseAddressModel')">Change Address
                    </button>
                    @push('modals')
                    @livewire('profile.detail.user.change-address-modal')
                    @endpush
                </div>
            </div>
            <div class="row col-12 mb-24">
                <div class="col-12">
                    <hr class="black-10 my-0">
                </div>
            </div>

            <div class="row col-12 mb-24">
                <div class="col-6">
                    <p class="col fw-bold fs-16 mb-3">{{ __('Phone') }}</p>
                    <p class="col text-black-50 fs-16">{{$user->phone? '+'.$user->phone_prefix.' '.$user->phone:'N/A'}}</p>
                </div>
                <div class="col-6 d-flex align-items-center justify-content-end">
                    @if(!$user->phone_verified && $user->phone)
                    <button type="button"
                        class="btn status-button pending fs-16 fw-normal button-secondary user-detail-btn"
                        wire:click.prevent="$emit('openCloseResendVerificationSMS')"
                    >
                        <svg class="icon icon-alert-circle me-2"
                            width="16"
                            height="16">
                            <use href="{{asset('img/icons.svg#icon-alert-circle')}}"></use>
                        </svg>
                        Pending Validation
                    </button>
                        @push('modals')
                        @livewire("profile.detail.user.resend-verification-s-m-s-modal",['user'=>$user])
                        @endpush
                    @else
                        <button
                            type="button"
                            class="btn button-secondary ms-auto user-detail-btn"
                            wire:click.prevent="$emit('openCloseChangePhoneModal')">Change Phone
                        </button>
                        @push('modals')
                        @livewire("profile.detail.user.change-phone-number-modal",['user'=>$user])
                        @endpush
                    @endif
                </div>
            </div>
            <div class="row col-12 mb-24">
                <div class="col-12">
                    <hr class="black-10 my-0">
                </div>
            </div>
            <div class="row col-12 mb-24">
                <div class="col-7">
                    <p class="col fw-bold fs-16 mb-30">User ID</p>
                    <label class="drop-area full" style="cursor: context-menu !important;">
                        <span class="fileElem"></span>
                        <span class="gallery">
                            <img src="{{ $id_proof?$id_proof->temporaryUrl():$user->id_proof }}"
                                alt="User Image">
                        </span>
                    </label>
                </div>
            </div>
        </form>
    </div>
</div>
