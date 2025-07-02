<div>
    <x-jet-dialog-modal id="bankModal" wire:model="showModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-32">
                <div class="modal-body p-0 row">
                    <x-slot name="title">
                        <div class="row col-12 p-0 m-0">
                            <h5 class="black-color col fw-bold mb-20">
                                {{$heading}}
                            </h5>
                            <button type="button"
                                class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2"
                                wire:click.prevent="openCloseBankFormModal" wire:loading.attr="disabled" aria-label="Close"></button>
                        </div>
                    </x-slot>
                    <x-slot name="content">
                        <form class="row col-12 p-0 m-0">
                            <div class="col-12 mb-24">
                                <label for="bank_name" class="form-label p-0 black-color">{{ __('Bank Name') }}</label>
                                <input type="text" id="bank_name" class="form-control default" wire:model.defer="bank_name" autocomplete="bank_name">
                                <x-jet-input-error for="bank_name" class="mt-2" />
                            </div>
                            <div class="col-12 mb-24">
                                <label for="bic" class="form-label p-0 black-color">{{ __('BIC') }}</label>
                                <input id="bic" type="tel" maxlength="11" wire:model.defer="bic" autocomplete="bic" class="form-control default">
                                <x-jet-input-error for="bic" class="mt-2" />
                            </div>
                            <div class="col-12 mb-24">
                                <label for="iban" class="form-label p-0 black-color">{{ __('IBAN') }}</label>
                                <input type="tel" maxlength="34" wire:model.defer="iban" autocomplete="iban" class="form-control default" id="iban"/>
                                <x-jet-input-error for="iban" class="mt-2" />
                            </div>
                            <div class="col-12 mb-24">
                                <label for="country_id" class="form-label p-0 black-color">{{ __('Bank Country') }}</label>
                                <select class="form-control default selectpicker" id="country_id" title=" " wire:model.defer="country_id" data-live-search="true">
                                    <option value="">-- Select Country --</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                                <x-jet-input-error for="country_id" class="mt-2" />
                            </div>
                            <div class="col-12 mb-50">
                                <label for="beneficiary_name" class="form-label p-0 black-color">{{ __('Beneficiary Name') }}</label>
                                <input type="text" class="form-control default" id="beneficiary_name" wire:model.defer="beneficiary_name" autocomplete="beneficiary_name" />
                                <x-jet-input-error for="beneficiary_name" class="mt-2" />
                            </div>
                            <div class="row col-12 m-0">
                                <a class="button-green w-100 mb-2 bank-submit-btn" href="#" wire:click="save()" wire:loading.attr="disabled">Save Bank Account</a>
                                <a class="button-green w-100 button-secondary"
                                wire:click.prevent="openCloseBankFormModal" wire:loading.attr="disabled"
                                    aria-label="Close" href="#">Cancel</a>
                            </div>
                        </form>
                    </x-slot>
                </div>
            </div>
        </div>
    </x-jet-dialog-modal>
</div>


@push('scripts')
    <script>
        document.addEventListener("livewire:load", function (event) {
            $('select.selectpicker').selectpicker('destroy');

            $('.bank-submit-btn').click(function(){
                $('select.selectpicker').selectpicker('destroy');
            })
        });
    </script>
@endpush
