<div>
    <x-jet-dialog-modal wire:model="showModal" data-backdrop="static" data-keyboard="false" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4">
                <div class="modal-body p-0">
                    <div class="row">
                        <x-slot name="title">
                            <div class="row col-12 p-0 m-0">
                                <h4 class="black-color col fw-bold mb-2">
                                    Add Company Details
                                </h4>
                                <button type="button"
                                    class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2" wire:click.prevent="openCloseAddNewCompanyModal" aria-label="Close"></button>
                            </div>
                        </x-slot>

                        <x-slot name="content">
                            <div class="col-12 col-lg row p-0 w-525" id="details-company">
                                <form method="POST" wire:submit.prevent>
                                    <div class="row col-12 p-0 m-0">
                                        <div class="col-12 mb-20">
                                            <div class="col-12">
                                                <label for="name" class="form-label p-0 black-color">{{ __('Company Name') }}</label>
                                                <input id="name"
                                                    type="text"
                                                    class="form-control default {{$errors->has('name')?'error':''}}"
                                                    wire:model="name"
                                                    autocomplete="name"
                                                />
                                                <x-jet-input-error for="name" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-12 mb-20">
                                            <div class="col-12">
                                                <label for="registration_id" class="form-label p-0 black-color {{$errors->has('registration_id')?'error':''}}">{{ __('Registration Id') }}</label>
                                                <input type="number"
                                                    wire:model="registration_id"
                                                    class="form-control default"
                                                    id="registration_id"
                                                />
                                                <x-jet-input-error for="registration_id" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-12 mb-20">
                                            <div class="col-12">
                                                <label for="registration_id" class="form-label p-0 black-color">{{ __('Field Of Business') }}</label>
                                                <input
                                                    id="field"
                                                    wire:model="field_of_business"
                                                    autocomplete="field"
                                                    type="text"
                                                    class="form-control default {{$errors->has('field_of_business')?'error':''}}"
                                                />
                                                <x-jet-input-error for="field_of_business" class="mt-2" />
                                            </div>
                                        </div>
                                        <div class="col-12 mb-20">
                                            <label class="form-label p-0 black-color">Upload Incorporation
                                                Documents</label>
                                            <label class="drop-area {{ !$errors->has('incorporation_document')?(($incorporation_document)?'full drop-area-cover':''):'' }}">
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
                                                @if (!$errors->has('incorporation_document'))
                                                    @if(!$is_incorporation_doc_img && !empty($incorporation_document) && in_array($file_extension,$fileMime))
                                                        <embed src="{{$incorporation_document->temporaryUrl()}}"/>
                                                    @elseif($incorporation_document && in_array($file_extension,$imageMimes))
                                                        <img src="{{ $incorporation_document->temporaryUrl() }}" alt="Prview">
                                                    @endif
                                                @endif
                                                </span>
                                            </label>
                                            <x-jet-input-error for="incorporation_document" class="mt-2" />
                                        </div>
                                        <div class="col-12 m-0">
                                            @push('modals')
                                                @livewire('profile.detail.company.change-company-address-modal')
                                            @endpush
                                            <button
                                                type="button"
                                                class="btn button-green w-100 mb-2"
                                                wire:click.prevent="saveCompanyDetails()"
                                            >Next
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </x-slot>
                        <x-slot name="footer">
                        </x-slot>
                    </div>
                </div>

            </div>
        </div>
    </x-jet-dialog-modal>
    <!-- Information about company -->
</div>


@push('scripts')
    <script>
        document.addEventListener("livewire:load", function (event) {
            $("#registration_id").on('keypress',function (event){
                if(event.charCode >= 48 && event.charCode <= 57) {
                    return true;
                } else {
                    event.preventDefault();
                }
            })
        });
    </script>
@endpush
