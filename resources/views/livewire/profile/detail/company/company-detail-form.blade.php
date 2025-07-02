<div>
    <!-- Information about company -->
    <div class="col-12 col-lg row p-0 w-525" id="details-company">
        <form method="POST" wire:submit.prevent>
            <div class="row col-12 mb-24">
                <div class="col-12">
                    <hr class="black-10 my-0">
                </div>
            </div>
            <div class="row col-12 mb-24">
                <div class="col-7">
                    <p class="col fw-bold fs-16 mb-3">{{ __('Approval Status') }}</p>
                    <p class="col text-black-50 fs-16">
                        <div class="status-button @if($company->status == 0) pending @elseif($company->status == 1) approved @else notapproved @endif  fw-normal ms-2">
                            @if($company->status == 0) Pending @elseif($company->status == 1) Approved @else Declined @endif
                        </div>
                    </p>
                </div>
            </div>
            <div class="row col-12 mb-24">
                <div class="col-12">
                    <hr class="black-10 my-0">
                </div>
            </div>
            <div class="row col-12 mb-24">
                <div class="col-6">
                    <p class="col fw-bold fs-16 mb-3">{{ __('Company Name') }}</p>
                    @if($disabled["name"] || !$company->status)
                        <p class="col text-black-50 fs-16">{{$company->name}}</p>
                    @else
                        <input id="name"
                            type="text"
                            class="default col text-black-50 fs-16"
                            wire:model.defer="company_name"
                            autocomplete="name"
                        />
                    @endif
                    <x-jet-input-error for="company_name" class="mt-2" />
                </div>
                @if($company->status)
                <div class="col-6 d-flex align-items-center justify-content-end p-0">
                    @if($disabled["name"])
                        <button type="button"
                            class="btn button-secondary edit-input px-4"
                            wire:click.prevent="makeEditable('name')">Change Company Name
                        </button>
                    @else
                        <button type="button"
                            class="btn button-green edit-input px-4"
                            wire:click.prevent="saveCompanyName()">Save
                        </button>
                    @endif
                </div>
                @endif
            </div>
            <div class="row col-12 mb-24">
                <div class="col-12">
                    <hr class="black-10 my-0">
                </div>
            </div>
            <div class="row col-12 mb-24">
                <div class="col-7">
                    <p class="col fw-bold fs-16 mb-3">{{ __('Registration Id') }}</p>
                    <p class="col text-black-50 fs-16">{{$registration_id}}</p>
                </div>
            </div>
            <div class="row col-12 mb-24">
                <div class="col-12">
                    <hr class="black-10 my-0">
                </div>
            </div>
            <div class="row col-12 mb-24">
                <div class="col-7">
                    <p class="col fw-bold fs-16 mb-3">{{ __('Field Of Business') }}</p>
                    @if($disabled["field"] || !$company->status)
                    <p class="col text-black-50 fs-16">{{$this->company->field}}</p>
                    @else
                    <input
                        id="field"
                        wire:model.defer="company_field"
                        autocomplete="field"
                        type="text"
                        class="default col text-black-50 fs-16"
                    >
                    <x-jet-input-error for="company_field" class="mt-2" />
                    @endif
                </div>
                @if($company->status)
                <div class="col-5 d-flex align-items-center justify-content-end">
                    @if($disabled["field"])
                        <button
                            type="button"
                            class="btn button-secondary"
                            wire:click.prevent="makeEditable('field')"
                        >Change
                        </button>
                    @else
                        <button
                            type="button"
                            class="btn button-green"
                            wire:click.prevent="saveCompanyField()"
                        >Save
                        </button>
                    @endif
                </div>
                @endif
            </div>
            <div class="row col-12 mb-24">
                <div class="col-12">
                    <hr class="black-10 my-0">
                </div>
            </div>
            <div class="row col-12 mb-24">
                <div class="col-7">
                    <!--  -->
                    <!--  -->
                    <p class="col fw-bold fs-16 mb-3">{{ __('Company Address') }}</p>
                    <p class="col text-black-50 fs-16">{{$address}}</p>
                </div>
                @if($company->status || empty($address))
                <div class="col-5 d-flex align-items-center justify-content-end">
                    @push('modals')
                    @livewire('profile.detail.company.change-company-address-modal')
                    @endpush
                    <button
                        type="button"
                        class="btn button-secondary"
                        wire:click.prevent="$emit('openCloseCompanyAddressModel')">
                        Change Address
                    </button>
                </div>
                @endif
            </div>
            <div class="row col-12 mb-24">
                <div class="col-12">
                    <hr class="black-10 my-0">
                </div>
            </div>
            <div class="row col-12 mb-24">
                <div class="col-7">
                    <p class="col fw-bold fs-16 mb-30">{{ __('Incorporation Document') }}</p>
                    <label class="drop-area full">
                        <span class="fileElem"></span>
                        <span class="gallery">
                            @if(!$is_incorporation_doc_img && !empty($company->incorporation_doc_url) && in_array($file_extension,$fileMime))
                                <embed src="{{$company->incorporation_doc_url}}"/>
                            @elseif($company->incorporation_doc_url && in_array($file_extension,$imageMimes))
                                <img src="{{ $company->incorporation_doc_url }}" alt="Prview">
                            @endif
                        </span>
                    </label>
                </div>
                @if($company->status)
                <div class="col-5 d-flex align-items-center justify-content-end">
                    <button type="button" class="btn button-secondary" wire:click.prevent="$emit('openCloseIncorporationDocModal')">Change
                    </button>
                    @push('modals')
                    @livewire('profile.detail.company.incorporation-doc-upload-form')
                    @endpush
                </div>
                @endif
            </div>
        </form>
    </div>
</div>
