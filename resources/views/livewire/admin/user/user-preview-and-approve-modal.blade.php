<div>
    <x-jet-modal class="modal fade" wire:model="viewUser" >
        @if($verifyType == 'success')
            <div class="alert alert-success" role="alert">
                {!! $verifyMsg !!}
            </div>
        @endif

        @if($verifyType == 'error')
            <div class="alert alert-danger" role="alert">
                {!! $verifyMsg !!}
            </div>
        @endif
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4">
                <div class="modal-body p-0 row">
                    <div class="row col-12 p-0 m-0">
                        <h5 class="black-color col fw-bold mb-5">
                            Review User for Approval
                        </h5>
                        <button type="button"
                                class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2"
                                wire:click.prevent="closeApprovalModal()"
                                aria-label="Close"
                                wire:loading.attr="disabled">
                        </button>
                    </div>
                    <div class="col row pe-0">
                        <ul class="nav basic-style row mx-0 p-0 mb-30 mw-1000" id="detailsTabs" role="tablist">
                            <li class="nav-item col text-center p-0" role="presentation">
                                <a class="nav-link {{($activeTab == 'user_details') ? 'active' : ''}}" id="details-tab" data-bs-toggle="tab"
                                   data-bs-target="#details" type="button" role="tab" aria-controls="details"
                                   aria-selected="true">User Details</a>
                            </li>
                            <li class="nav-item col text-center p-0" role="presentation">
                                <a class="nav-link {{($activeTab == 'company_details') ? 'active' : ''}}" id="company-tab" data-bs-toggle="tab"
                                   data-bs-target="#company" type="button" role="tab" aria-controls="company"
                                   aria-selected="false" tabindex="-1">Company Details</a>
                            </li>
                            <li class="nav-item col text-center p-0" role="presentation">
                                <a class="nav-link {{($activeTab == 'premium_validation') ? 'active' : ''}}" id="premium-tab" data-bs-toggle="tab"
                                   data-bs-target="#premium" type="button" role="tab" aria-controls="company"
                                   aria-selected="false" tabindex="-1">Premium Validation</a>
                            </li>

                        </ul>
                        <!-- OLD -->
                        <div id="detailsTabsContent" class="tab-content mb-24 pe-0">
                            <div class="tab-pane fade {{($activeTab == 'user_details') ? 'show active' : ''}} row" id="details" role="tabpanel"
                                 aria-labelledby="details-tab">

                                <div class="row col-12  mb-3">
                                    <div class="col-12 border-bottom pb-3">
                                        <p class="col fw-bold fs-16 mb-3">{{ __('Name') }}</p>
                                        <p class="col text-black-50 fs-16">{{$name}}</p>
                                    </div>
                                </div>

                                <div class="row col-12 mb-3 ">
                                    <div class="col-12 border-bottom pb-3">
                                        <p class="col fw-bold fs-16 mb-3">{{ __('Email') }}</p>
                                        <p class="col text-black-50 fs-16">{{$email}}</p>
                                    </div>
                                </div>

                                <div class="row col-12  mb-3">
                                    <div class="col-12 border-bottom pb-3">
                                        <p class="col fw-bold fs-16 mb-3">{{ __('Address') }}</p>
                                        <p class="col text-black-50 fs-16">{{$address}}</p>
                                    </div>
                                </div>

                                <div class="row col-12  mb-3">
                                    <div class="col-12 border-bottom pb-3">
                                        <p class="col fw-bold fs-16 mb-3">{{ __('Phone Number') }}</p>
                                        <p class="col text-black-50 fs-16">{{$phone}}</p>
                                    </div>
                                </div>

                                <div class="row col-12  mb-3">
                                    <div class="col-12 pb-3">
                                        <p class="col fw-bold fs-16 mb-3">{{ __('User Id') }}</p>
                                        <div class="drop-area">
                                            <span class="fileElem"></span>
                                            <span class="gallery">
                                                            <img src="{{ $id_proof }}" alt="User Image">
                                                        </span>
                                        </div>
                                    </div>
                                </div>

                                @if($status == 0)
                                    <div class="col-12 d-flex flex-column justify-content-end mt-2">
                                        <a class="button-green w-100 button-send fw-normal mb-20" wire:click="approveUser({{$userId}})" href="javascript:void(0)">
                                            {{ __('Approve User') }}<svg class="icon icon-arrow-right ms-2" width="16" height="16">
                                                <use href="{{asset('img/icons.svg#icon-arrow-right')}}"></use>
                                            </svg></a>
                                        <a class="button-secondary w-100" wire:click="declineUser({{$userId}})" href="javascript:void(0)" >Decline User</a>
                                    </div>
                                @endif
                            </div>

                            <div class="tab-pane fade row {{($activeTab == 'company_details') ? 'show active' : ''}}" id="company" role="tabpanel" aria-labelledby="company-tab">
                                <div class="row col-12  mb-3">
                                    <div class="col-12 border-bottom pb-3">
                                        <p class="col fw-bold fs-16 mb-3">{{ __('Company Name') }}</p>
                                        <p class="col text-black-50 fs-16">{{($selectedUserCompany->name) ?? '---'}}</p>
                                    </div>
                                </div>

                                <div class="row col-12 mb-3 ">
                                    <div class="col-12 border-bottom pb-3">
                                        <p class="col fw-bold fs-16 mb-3">{{ __('Registration ID') }}</p>
                                        <p class="col text-black-50 fs-16">{{($selectedUserCompany->registration_id) ?? '---'}}</p>
                                    </div>
                                </div>

                                <div class="row col-12 mb-3 ">
                                    <div class="col-12 border-bottom pb-3">
                                        <p class="col fw-bold fs-16 mb-3">{{ __('Field Of Business') }}</p>
                                        <p class="col text-black-50 fs-16">{{($selectedUserCompany->field) ?? '---'}}</p>
                                    </div>
                                </div>

                                <div class="row col-12 mb-3 ">
                                    <div class="col-12 border-bottom pb-3">
                                        <p class="col fw-bold fs-16 mb-3">{{ __('Company Address') }}</p>
                                        <p class="col text-black-50 fs-16">
                                            @if($selectedUserCompany)
                                                {{$selectedUserCompany->street}}
                                                @if($selectedUserCompany->city)
                                                    {{', '.$selectedUserCompany->city}}
                                                @endif
                                                @if($selectedUserCompany->country)
                                                    {{', '.$selectedUserCompany->country->name}}
                                                @endif
                                            @else
                                                {{'---'}}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="row col-12  mb-3">
                                    <div class="col-12 pb-3">
                                        <p class="col fw-bold fs-16 mb-3">{{ __('Incorporation Document') }}</p>
                                        <label class="drop-area">
                                            <span class="fileElem"></span>
                                            <span class="gallery">
                                                            @if($selectedUserCompany)
                                                    <img src="{{$selectedUserCompany->incorporation_doc_url}}" alt="Incorporation Document">
                                                @endif
                                                        </span>
                                        </label>
                                    </div>
                                </div>

                                @if($selectedUserCompany  && $selectedUserCompany->status == 0)
                                    <div class="col-12 d-flex flex-column justify-content-end mt-2">
                                        <a class="button-green w-100 button-send fw-normal mb-20" wire:click="verifyCompany({{$selectedUserCompany->id}}, 1)" href="javascript:void(0)">
                                            {{ __('Approve Company') }}<svg class="icon icon-arrow-right ms-2" width="16" height="16">
                                                <use href="{{asset('img/icons.svg#icon-arrow-right')}}"></use>
                                            </svg></a>
                                        <a class="button-secondary w-100" wire:click="verifyCompany({{$selectedUserCompany->id}}, 2)" href="javascript:void(0)" >Decline Company</a>
                                    </div>
                                @endif
                            </div>

                            <div class="tab-pane fade row {{($activeTab == 'premium_validation') ? 'show active' : ''}}" id="premium" role="tabpanel" aria-labelledby="premium-tab">
                                <div class="row col-12 d-flex justify-content-between align-items-end pe-0 ">
                                    Premium Validation
                                </div>
                            </div>
                        </div>
                        <!-- OLD -->
                    </div>
                </div>
            </div>
        </div>
    </x-jet-modal>
</div>

<script>
    window.addEventListener('reloadPage', (e) => {
        setTimeout(function(){
            window.location.reload();
        },2000);
    });
</script>

