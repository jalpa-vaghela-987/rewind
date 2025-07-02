<div>
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
                @forelse ($cards as $card)
                <div class="row col-12 mb-3">
                    <div class="col-2 d-flex align-items-left">
                        <div class="flex items-center">
                            <input type="radio" name="primary_card_id" wire:model.defer="primary_card_id"
                                value="{{$card->id}}" id="primary_{{$card->id}}"/>
                        </div>
                    </div>
                    <label class="col-10 d-flex align-items-center" for="primary_{{$card->id}}">
                        <div class="col-auto me-4">
                            <svg class="icon icon-credit_card green-icon" width="24"
                                height="24">
                                <use href="{{asset('img/icons.svg#icon-credit_card')}}"></use>
                            </svg>
                        </div>
                        <div class="col">
                            <p class=" black-color fs-16 mb-2">{{str_replace(range(0,9), "X", substr($card->card_no, 0, -4)) .  substr($card->card_no, -4)}}</p>
                            <p class="black-color fs-16">{{$card->card_holder_name}}</p>
                        </div>
                    </label>
                </div>
                @empty
                @endforelse
                <div class="row col-12 mb-3">
                    <div class="col-2 d-flex align-items-left">
                        <div class="flex items-center">
                            <input type="radio" name="primary_card_id" wire:model.defer="primary_card_id" wire:change.prevent="showAddCreditCardmodal" id="add_new_card" value="card_id"/>
                        </div>
                    </div>
                    <label class="col-10 d-flex align-items-center" for="add_new_card">
                        <div class="col-auto me-4">
                            <svg class="icon icon-credit_card green-icon" width="24"
                                height="24">
                                <use href="{{asset('img/icons.svg#icon-credit_card')}}"></use>
                            </svg>
                        </div>
                        <div class="col">
                            <p class="black-color fs-16 mb-2">Add New Credit Card</p>
                            <x-jet-input-error for="primary_card_id" class="mt-2" />
                        </div>
                    </label>
                </div>
                <div class="row col-12 m-0">
                    <button type="button" wire:loading.attr="disabled"
                            class="button-green w-100 mb-2" wire:click.prevent="buy" disableed="{{!$primary_card_id?true:false}}">Proceed
                    </button>
                    <button type="button" wire:click.prevent="closeModal"
                            class="button-green w-100 button-secondary">Cancel
                    </button>
                </div>
                <!-- Card Work Ends Here -->
            </div>
        </div>
    </x-jet-modal>
    @livewire('buy.add-card-modal')
</div>
