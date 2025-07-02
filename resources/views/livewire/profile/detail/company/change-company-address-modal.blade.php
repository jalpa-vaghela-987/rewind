<div>
    <x-jet-dialog-modal wire:model="change_address" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4">
                <div class="modal-body p-0 row">
                    <div class="row col-12 p-0 m-0">
                        <x-slot name="title">
                            <div class="row col-12 p-0 m-0">
                                <h5 class="black-color col fw-bold mb-3">
                                    Company Address
                                </h5>
                                <button type="button" class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2" wire:click.prevent="openCloseAddressModel" aria-label="Close"></button>
                            </div>
                        </x-slot>

                        <x-slot name="content">
                            <div class="row col-12 p-0 m-0">
                                <div class="row mx-0 mb-24">
                                    <label for="Street" class="form-label p-0 black-color">{{ __('Street') }}</label>
                                    <input id="street" type="text" class="form-control default" wire:model.defer="street" autocomplete="street"/>
                                    <x-jet-input-error for="street" class="mt-2" />
                                </div>
                                <div class="row mx-0 mb-24 gap-3">
                                    <div class="col row">
                                        <label for="city" class="form-label p-0 black-color">{{ __('City') }}</label>
                                        <input id="city" type="text" class="form-control default" id="City" wire:model.defer="city" autocomplete="city"/>
                                        <x-jet-input-error for="city" class="mt-2" />
                                    </div>
                                    <div class="col row">
                                        <label for="country_id" class="form-label p-0 black-color">{{ __('Country') }}</label>
                                        <select class="form-control default selectpicker" id="country_id" title="Select Country" data-live-search="true"  wire:model.defer="country_id">
                                            <option value="">-- Select Country --</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                        <x-jet-input-error for="country_id" class="mt-2" />
                                    </div>
                                </div>
                                <div class="col-12 d-flex flex-column justify-content-end mt-2">
                                    @push('modals')
                                        @livewire('profile.detail.company.new-company-detail-success-modal')
                                    @endpush
                                    <a class="button-green w-100 save-address-btn" href="javascript:void(0);" wire:click.prevent="save()" wire:loading.attr="disabled">{{ __('Save') }}</a>
                                </div>
                            </div>
                        </x-slot>
                    </div>
                </div>
            </div>
        </div>

        <x-slot name="footer">
        </x-slot>
    </x-jet-dialog-modal>
</div>

@push('scripts')
    <script>
        document.addEventListener("livewire:load", function (event) {
            $('select.selectpicker').selectpicker('destroy');

            Livewire.hook('message.processed', () => {
                $('.save-address-btn').click(function () {
                    $('select.selectpicker').selectpicker('destroy');
                })
            });
        });
    </script>
@endpush
