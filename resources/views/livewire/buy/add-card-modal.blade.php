<div>
    <!-- Card Modal -->
    <x-jet-modal class="modal fade" wire:model="showModal">
        <div class="modal-content p-32">
            <div class="modal-body p-0 row">
                <div class="row col-12 p-0 m-0">
                    <h5 class="black-color col-7 fw-bold mb-20">
                        Payment information is required before proceeding
                    </h5>
                    <button type="button"
                            wire:click.prevent="closeModal"
                            class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2"></button>
                </div>
                <!-- Card Work Goes Here -->
                <form wire:submit.prevent="saveNewCard" class="row col-12 p-0 m-0">
                    <input type="hidden" name="payment_method" class="payment-method">
                    <div class="col-12 mb-20">
                        <label for="Card" class="form-label p-0 black-color">Credit Card</label>
                        <div class="input-wrap">
                            <input type="number"
                                onKeyPress="if(this.value.length==16) return false;"
                                class="form-control default"
                                maxlength="16"
                                id="card_no"
                                wire:model.defer="card_no"
                                autocomplete="card_no"
                                placeholder="xxx xxx xxxx xxxx">
                            <span class="icon">
                                <svg class="icon icon-credit_card" width="24" height="24">
                                    <use href="{{asset('img/icons.svg#icon-credit_card')}}"></use>
                                </svg>
                            </span>
                        </div>
                        <x-jet-input-error for="card_no" class="mt-2"/>
                    </div>
                    <div class="col-12 mb-20">
                        <label for="Name" class="form-label p-0 black-color">Name</label>
                        <input type="text" id="card_holder_name" class="form-control default" id="Name"
                            placeholder="Cardholder Name" wire:model.defer="card_holder_name">
                        <x-jet-input-error for="card_holder_name" class="mt-2"/>
                    </div>
                    <div class="col-12 d-flex justify-content-between mb-20">
                        <div class="col-auto fs-16 d-flex flex-column justify-content-end align-items-start">
                            <label for="Expiry" class="form-label p-0 black-color">Expiry</label>
                            <div class="row m-0">
                                <input type="number"
                                    onKeyPress="if(this.value.length==2) return false;"
                                    class="form-control default w-66"
                                    id="expiry_month"
                                    wire:model.defer="expiry_month"
                                    placeholder="MM">
                                <input type="number"
                                    onKeyPress="if(this.value.length==4) return false;"
                                    class="form-control default w-66 ms-2"
                                    id="expiry_year"
                                    wire:model.defer="expiry_year"
                                    placeholder="YYYY">
                            </div>
                            @if($errors->has('expiry_month') || $errors->has('expiry_year'))
                            <x-jet-input-error for="expiry_month" class="mt-2"/>
                            <x-jet-input-error for="expiry_year" class="mt-2"/>
                            @else
                            <x-jet-input-error for="expiry" class="mt-2"/>
                            @endif
                        </div>
                        <div class="col-auto fs-16 d-flex flex-column justify-content-center align-items-start">
                            <label for="CVV" class="form-label p-0 black-color">CVV</label>
                            <div class="row m-0">
                                <input type="number"
                                    onKeyPress="if(this.value.length==3) return false;"
                                    class="form-control default w-66"
                                    id="CVV"
                                    wire:model.defer="cvv"
                                    placeholder="CVV">
                            </div>
                            <x-jet-input-error for="cvv" class="mt-2"/>
                        </div>
                    </div>
                    <div class="row col-12 m-0">
                        <button type="submit" wire:loading.attr="disabled"
                                class="button-green w-100 mb-2">Save Payment Details
                        </button>
                        <button type="button" wire:click.prevent="closeModal"
                                class="button-green w-100 button-secondary">Cancel
                        </button>
                    </div>
                </form>
                <!-- Card Work Ends Here -->
            </div>
        </div>
    </x-jet-modal>
    <!-- Card Modal END -->
</div>
