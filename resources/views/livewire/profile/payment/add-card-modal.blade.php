<div>
    <x-jet-dialog-modal wire:model="showModal" data-backdrop="static" data-keyboard="false" id="cardModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-32">
                <div class="modal-body p-0 row">
                    <x-slot name="title">
                        <div class="row col-12 p-0 m-0">
                            <h5 class="black-color col-7 fw-bold mb-20">
                                {{$heading}}
                            </h5>
                            <button type="button"
                                class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2"
                                wire:click.prevent="openCloseAddCardModal"
                                wire:loading.attr="disabled">
                            </button>
                        </div>
                    </x-slot>
                    <x-slot name="content">
                        <form class="row col-12 p-0 m-0">
                            <div class="col-12 mb-20">
                                <label for="card_no" class="form-label p-0 black-color">{{ __('Credit Card') }}</label>
                                <div class="input-wrap">
                                    <input id="card_no"
                                           type="number" onKeyPress="if(this.value.length==16) return false;"
                                        maxlength="16"
                                        class="form-control default"
                                        wire:model.defer="card_no"
                                        autocomplete="card_no"
                                        placeholder="xxx xxx xxxx xxxx"
                                    />
                                    <span class="icon">
                                        <svg class="icon icon-credit_card" width="24" height="24">
                                            <use href="{{asset('img/icons.svg#icon-credit_card')}}"></use>
                                        </svg>
                                    </span>
                                </div>
                                <x-jet-input-error for="card_no" class="mt-2" />
                            </div>
                            <div class="col-12 mb-20">
                                <label for="card_holder_name" class="form-label p-0 black-color">{{ __('Name') }}</label>
                                <input type="text" class="form-control default specialChar" id="card_holder_name" placeholder="Cardholder Name" wire:model.defer="card_holder_name" autocomplete="card_holder_name"/>
                                <x-jet-input-error for="card_holder_name" class="mt-2" />
                            </div>
                            <div class="col-12 d-flex justify-content-between mb-0 align-items-start">
                                <div class="col-6 fs-16 d-flex flex-column justify-content-end align-items-start">
                                    <label for="expiry_month" class="form-label p-0 black-color">{{ __('Expiry') }}</label>
                                    <div class="row m-0">
                                        <input type="number" onKeyPress="if(this.value.length==2) return false;" class="form-control default w-66" id="expiry_month" placeholder="MM" maxlength="2" wire:model.defer="expiry_month" autocomplete="expiry_month"/>
                                        <input type="number" onKeyPress="if(this.value.length==4) return false;" class="form-control default w-66 ms-2" placeholder="YYYY" id="expiry_year" maxlength="4" wire:model.defer="expiry_year" autocomplete="expiry_year"/>
                                    </div>
                                    @if($errors->has('expiry_month') || $errors->has('expiry_year'))
                                        <x-jet-input-error for="expiry_month" class="mt-2"/>
                                        <x-jet-input-error for="expiry_year" class="mt-2"/>
                                    @else
                                        <x-jet-input-error for="expiry" class="mt-2"/>
                                    @endif
                                </div>
                                <div class="col-6 fs-16 d-flex flex-column justify-content-center align-items-end">
                                    <div>
                                        <label for="cvv" class="form-label p-0 black-color">{{ __('CVV') }}</label>
                                        <div class="row col-12 m-0">
                                            <input id="cvv"type="number" onKeyPress="if(this.value.length==3) return false;" maxlength="4" class="form-control default w-66" placeholder="CVV" wire:model.defer="cvv" autocomplete="cvv">
                                        </div>
                                    </div>
                                    <x-jet-input-error for="cvv" class="mt-2"/>
                                </div>
                            </div>
                            <div class="row col-12 mt-4 m-0">
                                <a class="button-green w-100 mb-2" href="#" wire:click="save()" wire:loading.attr="disabled">Save Credit Card</a>
                                <a class="button-green w-100 button-secondary"
                                    href="#" wire:click.prevent="openCloseAddCardModal">Cancel</a>
                            </div>
                        </form>
                    </x-slot>
                    <x-slot name="footer">

                    </x-slot>
                </div>
            </div>
        </div>
    </x-jet-dialog-modal>
</div>
