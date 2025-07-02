<div>
    <div class="row col-12 d-flex justify-content-between align-items-start pe-0 ">
        <div class="col-12 col-lg row p-3 w-525" id="credit-card">
            <div class="row col-12 mb-40">
                <div class="col-6 d-flex align-items-center">
                    <p class="col fw-bold fs-16">Credit Card</p>
                </div>
                <div class="col-6 d-flex align-items-center justify-content-end">
                    <button type="button" class="btn button-green px-4"
                            wire:click="$emit('openCloseAddCardModal')">
                        <svg class="icon icon-plus me-2" width="16" height="16">
                            <use href="{{asset('img/icons.svg#icon-plus')}}"></use>
                        </svg>
                        Add Credit Card
                    </button>
                </div>
            </div>
            @error('primary_card_id')
            <div class="mb-4">
                <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                    <li>{{ $message }}</li>
                </ul>
            </div>
            @enderror
            @forelse ($cards as $card)
                <div class="row col-12 mb-3">
                    <div class="col-1 d-flex align-items-left">
                        <label class="flex items-center">
                            <input type="radio" name="primary_card_id" wire:model="primary_card_id"
                                   value="{{$card->id}}"/>
                        </label>
                    </div>
                    <div class="col-6 d-flex align-items-center">
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
                    </div>
                    <div class="col-5 d-flex align-items-top justify-content-end">
                        <button type="button" class="btn button-secondary px-4"
                                wire:click.prevent="$emit('openCloseAddCardModal',{{$card->id}})">Edit Credit Card
                        </button>
                    </div>
                </div>
            @empty
                <!--  No Credit Card -->
                <div class="row col-12 mb-40">
                    <div
                            class="col-12 d-flex align-items-center justify-content-center">
                        <p
                                class="fs-16 empty-field w-100 text-center d-flex align-items-center justify-content-center">
                            No Credit Card</p>
                    </div>
                </div>
            @endforelse
            @push('modals')
                @livewire("profile.payment.add-card-modal")
            @endpush
            <div class="row col-12 mb-24">
                <div class="col-12">
                    <hr class="black-10 my-0">
                </div>
            </div>
        </div>
        <div class="col-12 col-lg row p-3 w-525" id="bank-account">
            <div class="row col-12 mb-40">
                <div class="col-6 d-flex align-items-center">
                    <p class="col fw-bold fs-16">Bank account</p>
                </div>
                <div class="col-6 d-flex align-items-center justify-content-end">
                    <button type="button" class="btn button-green px-4"
                            wire:click="$emit('openCloseBankFormModal')">
                        <svg class="icon icon-plus me-2" width="16" height="16">
                            <use href="{{asset('img/icons.svg#icon-plus')}}"></use>
                        </svg>
                        Add Bank account
                    </button>
                </div>
            </div>
            @error('primary_bank_id')
            <div class="mb-4">
                <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                    <li>{{ $message }}</li>
                </ul>
            </div>
            @enderror
            @forelse ($banks as $bank)
                <div class="row col-12 mb-3">
                    <div class="col-1 d-flex align-items-left">
                        <label class="flex items-center">
                            <input type="radio" name="primary_bank_id" wire:model="primary_bank_id"
                                   value="{{$bank->id}}"/>
                        </label>
                    </div>
                    <div class="col-5">
                        <div class="col-12 mb-24">
                            <p class=" black-color fs-20 fw-bold mb-2">Bank Name</p>
                            <p class="fs-16 text-black-50">{{$bank->name}}</p>
                        </div>
                        <div class="col-12 mb-24">
                            <p class=" black-color fs-20 fw-bold mb-2">BIC</p>
                            <p class="fs-16 text-black-50">{{$bank->bic}}</p>
                        </div>
                        <div class="col-12 mb-24">
                            <p class=" black-color fs-20 fw-bold mb-2">IBAN</p>
                            <p class="fs-16 text-black-50">{{preg_replace("/[A-Za-z0-9]/", "X", substr($bank->iban, 0, -4)) .  substr($bank->iban, -4)}}</p>
                        </div>
                        <div class="col-12 mb-24">
                            <p class=" black-color fs-20 fw-bold mb-2">Bank Country</p>
                            <p class="fs-16 text-black-50">{{$bank->country->name}}</p>
                        </div>
                        <div class="col-12">
                            <p class=" black-color fs-20 fw-bold mb-2">Beneficiary Name
                            </p>
                            <p class="fs-16 text-black-50">{{$bank->beneficiary_name}}</p>
                        </div>
                    </div>
                    <div class="col-6 d-flex align-items-top justify-content-end">
                        <button type="button" class="btn button-secondary px-4"
                                wire:click.prevent="$emit('openCloseBankFormModal',{{$bank->id}})">Edit Bank Account
                        </button>
                    </div>
                </div>
                <div class="row col-12 mb-24">
                    <div class="col-12">
                        <hr class="black-10 my-0">
                    </div>
                </div>
            @empty
                <!-- No bank -->
                <div class="row col-12 mb-40">
                    <div
                            class="col-12 d-flex align-items-center justify-content-center">
                        <p
                                class="fs-16 empty-field w-100 text-center d-flex align-items-center justify-content-center">
                            No Bank Account</p>

                    </div>
                </div>
            @endforelse
            @push('modals')
                @livewire("profile.payment.bank-form-modal")
            @endpush
        </div>
    </div>
</div>
