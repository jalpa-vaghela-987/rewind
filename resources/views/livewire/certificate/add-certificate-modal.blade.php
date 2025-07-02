<div>
{{--<div class="modal fade" wire:model="showModal"  data-backdrop="static" data-keyboard="false" id="addCertifModal1" tabindex="-1" >--}}
    <x-jet-dialog-modal wire:model="showModal" class="modal fade" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4">
            <div class="modal-body p-0 row">
                <div class="row col-12 p-0 m-0">
                    <x-slot name="title">
                        <h5 class="black-color col fw-bold mb-4">
                        Add Carbon Credit
                        <button type="button" class="btn-close opacity-1 float-right align-top align-self-start ms-auto col-auto me-2" wire:click.prevent="closeAddCertificateModal" aria-label="Close"></button>
                        </h5>
                    </x-slot>


                </div>
                <x-slot name="content">
                {{--<form class="row col-12 p-0 m-0">--}}
                    <div class="col-12 mb-30">
                        <select class="form-control default select-option" id="project_type_id" data-live-search="true" wire:model.defer="project_type_id" >
                            <option value="">Select Project Type</option>
                            @foreach($projectTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->type }}</option>
                            @endforeach
                        </select>
                        <x-jet-input-error for="project_type_id" class="mt-2" />
                    </div>
                    <div class="col-12 mb-30">

                        <label for="project_name" class="form-label p-0 black-color">{{ __('Project Name') }}</label>
                        <input type="text" class="form-control default" wire:model.defer="name" id="name">
                        <x-jet-input-error for="name" class="mt-2" />
                    </div>
                    <div class="col-12 mb-30" >
                        <label for="country_id" class="form-label p-0 black-color">{{ __('Country') }}</label>
                        <select class="form-control default select-option" id="country_id" data-live-search="true" wire:model.defer="country_id" >
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                        <x-jet-input-error for="country_id" class="mt-2" />
                    </div>
                    <div class="col-12 mb-30">
                        <x-jet-label for="quantity" class="form-label p-0 black-color" value="{{ __('Quantity') }}" />
                        <x-jet-input id="quantity" type="number" class="form-control default" data-live-search="true" wire:model.defer="quantity" autocomplete="quantity"/>
                        <x-jet-input-error for="quantity" class="mt-2" />
                    </div>
                    <div class="col-12 mb-30">
                        <x-jet-label for="price" class="form-label p-0 black-color" value="{{ __('Total Price') }}" />
                        <x-jet-input id="price" type="number"  onKeyPress="if(this.value.length==16) return false;" maxlength="16" class="form-control default" wire:model.defer="price" autocomplete="price"/>
                        <x-jet-input-error for="price" class="mt-2" />
                        <x-jet-input-error for="price_per_unit" class="mt-2" />
                    </div>

                    <div class="col-12 mb-30" >
                        <label for="project_year" class="form-label p-0 black-color">{{ __('Project Year') }}</label>
                        <select class="form-control default select-option" id="project_year" data-live-search="true" wire:model.defer="project_year" >
                            <option value="">Select a Project year</option>
                            @for ($year = date('Y'); $year >= 1900; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                        <x-jet-input-error for="project_year" class="mt-2" />
                    </div>

                    <div class="col-12 mb-30" >
                        <label for="vintage" class="form-label p-0 black-color">{{ __('Vintage') }}</label>
                        <select class="form-control default select-option" id="vintage" data-live-search="true" wire:model.defer="vintage" >
                            <option value="">Select a Vintage year</option>
                            @for ($year = date('Y'); $year >= 1900; $year--)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                        <x-jet-input-error for=" on view page there is carbon credit details section having carbon credit field title show below each other" class="mt-2" />
                    </div>

                    <div class="col-12 mb-30">
                        <x-jet-label for="total_size" class="form-label p-0 black-color" value="{{ __('Total Size (in Acers)') }}" />
                        <x-jet-input id="total_size" type="number"  onKeyPress="if(this.value.length==16) return false;" maxlength="16" class="form-control default" wire:model.defer="total_size" autocomplete="total_size"/>
                        <x-jet-input-error for="total_size" class="mt-2" />
                    </div>
                    <div class="col-12 mb-30">
                        <x-jet-label for="approving_body" class="form-label p-0 black-color" value="{{ __('Approving Body') }}" />
                        <x-jet-input id="approving_body" type="text"  class="form-control default" wire:model.defer="approving_body" autocomplete="approving_body"/>
                        <x-jet-input-error for="approving_body" class="mt-2" />
                    </div>
                    <div class="col-12 mb-30">
                        <x-jet-label for="link_to_certificate" class="form-label p-0 black-color" value="{{ __('Link to Certificate') }}" />
                        <x-jet-input id="link_to_certificate" type="text"  class="form-control default" wire:model.defer="link_to_certificate" autocomplete="link_to_certificate"/>
                        <x-jet-input-error for="link_to_certificate" class="mt-2" />
                    </div>
                    <div class="col-12 d-flex flex-column justify-content-end mt-2">
                        <x-secondary-link-button class="button-green w-100 button-send fw-normal mb-40" wire:click="save()" wire:loading.attr="disabled">
                            {{ __('Send for Approval') }}
                        </x-secondary-link-button>

                        {{--<a class="button-green w-100 button-send fw-normal mb-40" wire:click="save()">{{ __('Send for Approval') }}
                            <svg class="icon icon-arrow-right ms-2" width="16" height="16">
                                <use href="./img/icons.svg#icon-arrow-right"></use>
                            </svg></a>--}}
{{--                        <p class="text-center fs-16 black-color px-4">after sending for approval a messsage should notify the user that his sell is being reviewed for approval that may take x days/hours/minutes</p>--}}
                    </div>
                {{--</form>--}}
                </x-slot>
                <x-slot name="footer">
                </x-slot>
            </div>
        </div>
    </div>
    </x-jet-dialog-modal>

    {{--<div>
        <x-jet-dialog-modal wire:model="showModal" data-backdrop="static" data-keyboard="false">
            <x-slot name="title">
                Add Certificate
            </x-slot>

            <x-slot name="content">
                <div class="col-span-12 sm:col-span-8 full-width">
                    <x-jet-label for="project_type_id" value="{{ __('Project Type') }}" />
                    <select class="form-control border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-100" id="project_type_id" wire:model.defer="project_type_id">
                        <option value="">-- Select Project Type --</option>
                        @foreach($projectTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->type }}</option>
                        @endforeach
                    </select>
                    <x-jet-input-error for="project_type_id" class="mt-2" />
                </div>
                <div class="col-span-12 sm:col-span-8">
                    <x-jet-label for="project_name" value="{{ __('Project Name') }}" />
                    <x-jet-input id="name" type="text" class="mt-1 block w-100" wire:model.defer="name" autocomplete="name"/>
                    <x-jet-input-error for="name" class="mt-2" />
                </div>
                <div class="col-span-12 sm:col-span-8">
                    <x-jet-label for="country_id" value="{{ __('Country') }}" />
                    <select class="form-control border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm mt-1 block w-50" id="country_id" wire:model.defer="country_id">
                        <option value="">-- Select Country --</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                    <x-jet-input-error for="country_id" class="mt-2" />
                </div>
                <div class="col-span-12 sm:col-span-8">
                    <x-jet-label for="quantity" value="{{ __('Quantity') }}" />
                    <x-jet-input id="quantity" type="number"  class="mt-1 block w-50" wire:model.defer="quantity" autocomplete="quantity"/>
                    <x-jet-input-error for="quantity" class="mt-2" />
                </div>
                <div class="col-span-12 sm:col-span-8">
                    <x-jet-label for="price" value="{{ __('Price') }}" />
                    <x-jet-input id="price" type="text" maxlength="16" class="mt-1 block w-50" wire:model.defer="price" autocomplete="price"/>
                    <x-jet-input-error for="price" class="mt-2" />
                </div>
                <div class="col-span-12 sm:col-span-8">
                    <x-jet-label for="approving_body" value="{{ __('Approving Body') }}" />
                    <x-jet-input id="approving_body" type="text"  class="mt-1 block w-50" wire:model.defer="approving_body" autocomplete="approving_body"/>
                    <x-jet-input-error for="approving_body" class="mt-2" />
                </div>
                <div class="col-span-12 sm:col-span-8">
                    <x-jet-label for="link_to_certificate" value="{{ __('Link to Certificate') }}" />
                    <x-jet-input id="link_to_certificate" type="text"  class="mt-1 block w-50" wire:model.defer="link_to_certificate" autocomplete="link_to_certificate"/>
                    <x-jet-input-error for="link_to_certificate" class="mt-2" />
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click.prevent="openCloseAddCertificateModal" wire:loading.attr="disabled">
                    {{ __('Cancel') }}
                </x-jet-secondary-button>

                <x-jet-danger-button class="ml-2" wire:click="save()" wire:loading.attr="disabled">
                    {{ __('Send for Approval') }}
                </x-jet-danger-button>
            </x-slot>
        </x-jet-dialog-modal>
    </div>--}}
    </div>

@push('scripts')
    <script>
        document.addEventListener("livewire:load", function (event) {
            $('select.selectpicker').selectpicker('destroy');

            Livewire.hook('message.processed', () => {
                $('.button-send').click(function(){
                    $('select.selectpicker').selectpicker('destroy');
                })
            });

            $("#quantity, #price, #total_size").on('keypress',function (event){
                if(event.charCode >= 48 && event.charCode <= 57) {
                    return true;
                } else {
                    event.preventDefault();
                }
            })
        });
    </script>
@endpush
