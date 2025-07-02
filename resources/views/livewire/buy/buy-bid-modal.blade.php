<div>
    <x-jet-modal class="modal fade" wire:model="showModal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content  p-32">
                <div class="modal-body p-0 row">
                    <div class="row col-12 p-0 m-0">
                        <h5 class="black-color col fw-bold mb-20">
                            <svg class="icon icon-Forest-ERB me-2" width="32" height="32">
                                <use
                                    href="{{asset('img/icons.svg#'.@$selectedCertificate->certificate->project_type->image_icon)}}"></use>
                            </svg>
                            {{ $this->title }}
                        </h5>
                        <button wire:click.prevent="closeModal" type="button"
                                class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2"></button>
                        <div class="col-12 d-flex align-items-baseline mb-20">
                            <h5 class="price fw-bold me-3">${{ price_format($pricePerUnit) }}</h5>
                            <?php /*<span class="statistic-price statistic-increase d-flex align-items-center fs-12">
                            <svg class="icon icon-triangle-top me-1" width="8" height="8">
                                <use href="{{asset('/img/icons.svg#icon-triangle-top')}}"></use>
                            </svg>{{$price_difference}} ({{($price_average > 0) ? '+' : '-'}}{{$price_average}})</span>*/?>
                        </div>
                    </div>
                    <div class="row col-12 mx-0 black-10 mb-24">
                        <hr class="m-0">
                    </div>
                    <form class="row col-12 p-0 m-0">
                        <div class="col-12">
                            <ul class="nav basic-style row mx-0 mb-30" id="buyModalTabs" role="tablist">
                                <li class="nav-item col text-center p-0" role="presentation">
                                    <a class="nav-link {{($activeTab == 'buy') ? 'active' : ''}}" href="#"
                                    wire:click.prevent="changeTab('buy')">Buy</a>
                                </li>
                                <li class="nav-item col text-center p-0" role="presentation">
                                    <a class="nav-link {{($activeTab == 'bid') ? 'active' : ''}}" href="#"
                                    role="tab" wire:click.prevent="changeTab('bid')">Place Bid</a>
                                </li>
                            </ul>
                            <div class="tab-content mb-24" id="buyModalTabsContent">
                                <div class="tab-pane fade  {{($activeTab == 'buy') ? 'active show' : ''}} row"
                                    id="buy" role="tabpanel"
                                    aria-labelledby="buy-tab">
                                    <div class="col-12 row align-items-center  d-flex pe-0">
                                        <div class="col-3"><label for="QTY"
                                                                class="fs-16">{{ucfirst($showData)}}</label>
                                        </div>
                                        @if($showData == 'cost')
                                            <div class="col-9 justify-content-end justify-content-md-start col-md-6 pe-0 pe-md-2 d-flex">
                                                    <span class="input-group-prepend col-auto">
                                                        <button type="button"
                                                                class="btn button-green btn-number"
                                                                wire:click.prevent="decrease"><svg
                                                                class="icon icon-Minus-Icon"
                                                                width="24" height="24">
                                                                <use
                                                                    href="{{asset('/img/icons.svg#icon-Minus-Icon')}}"></use>
                                                            </svg>
                                                        </button>
                                                    </span>
                                                <input type="number" wire:model="cost"
                                                    class="form-control default mx-2 w-50 text-center fw-bold"
                                                    name="cost"
                                                    id="cost" min="1"
                                                    disabled>
                                                <span class="input-group-append col-auto">
                                                    <button type="button" class="btn button-green btn-number"
                                                            wire:click.prevent="increase">
                                                        <svg class="icon icon-Plus-Icon" width="24" height="24">
                                                            <use href="{{asset('/img/icons.svg#icon-Plus-Icon')}}"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                            </div>
                                        @else
                                            <div class="col-6 d-flex">
                                                    <span class="input-group-prepend col-auto">
                                                        <button type="button"
                                                                class="btn button-green btn-number {{$units}}"
                                                                wire:click.prevent="decrease" :disabled="{{$units==0?'true':'false'}}"><svg
                                                                class="icon icon-Minus-Icon"
                                                                width="24" height="24">
                                                                <use
                                                                    href="{{asset('/img/icons.svg#icon-Minus-Icon')}}"></use>
                                                            </svg>
                                                        </button>
                                                    </span>
                                                <input type="number" wire:model="units"
                                                    class="form-control default mx-2 w-50 text-center fw-bold"
                                                    name="QTY"
                                                    id="QTY" min="1" max="100" value="75"
                                                    wire:input.prevent.debounce.1000ms="changeUnits">
                                                <span class="input-group-append col-auto">
                                                    <button type="button" class="btn button-green btn-number"
                                                            wire:click.prevent="increase"
                                                            :disabled="{{$units>=$total_reamining_units?'true':'false'}}">
                                                        <svg class="icon icon-Plus-Icon" width="24" height="24">
                                                            <use href="{{asset('/img/icons.svg#icon-Plus-Icon')}}"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                            </div>
                                        @endif
                                        <!-- <div class="col-12 col-md-3 pt-2 pt-md-0 pe-0">
                                            <a class="button-secondary w-100" href="#"
                                            wire:click.prevent="switchShowData">
                                                <svg
                                                    class="icon icon-Repeat me-2" width="16" height="16">
                                                    <use href="{{asset('/img/icons.svg#icon-Repeat')}}"></use>
                                                </svg> {{($showData == 'cost') ? 'Units' : 'Cost' }}</a>
                                        </div> -->
                                        <x-jet-input-error for="units" class="mt-2" />
                                        <x-jet-input-error for="error" class="mt-2"/>
                                    </div>
                                </div>
                                <div class="tab-pane fade row {{($activeTab == 'bid') ? 'active show' : ''}}"
                                    id="bid" role="tabpanel" aria-labelledby="bid-tab">
                                    <div class="col-12 row align-items-center mb-24 d-flex pe-0">
                                        <div class="col-3"><label for="Rate" class="fs-16">Rate</label>
                                        </div>

                                        <div class="col-9 justify-content-end justify-content-md-start col-md-6 pe-0 pe-md-2 d-flex">
                                            <span class="input-group-prepend col-auto">
                                                <button wire:click.prevent="decreaseRate" type="button"
                                                    class="btn button-green btn-number"><svg
                                                    class="icon icon-Minus-Icon" width="24" height="24"
                                                    :disabled="{{$rate<=0?'true':'false'}}"
                                                >
                                                        <use href="{{asset('/img/icons.svg#icon-Minus-Icon')}}"></use>
                                                    </svg>
                                                </button>
                                            </span>
                                            <input type="number" wire:model="rate"
                                                    class="form-control default mx-2 w-50 text-center fw-bold"
                                                    name="Rate"
                                                    id="Rate" min="0" max="100" value="75"
                                                    wire:input.prevent.debounce.1000ms="changeBidRate"
                                                    >
                                            <span class="input-group-append col-auto">
                                                <button wire:click.prevent="increaseRate" type="button"
                                                    class="btn button-green btn-number"
                                                    :disabled="{{$rate>=$pricePerUnit?'true':'false'}}"
                                                >
                                                    <svg class="icon icon-Plus-Icon" width="24" height="24">
                                                        <use href="{{asset('/img/icons.svg#icon-Plus-Icon')}}"></use>
                                                    </svg>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-12 row align-items-center mb-24 d-flex pe-0">
                                        @if($bidShowData == 'cost')
                                            <div class="col-3"><label for="Amount" class="fs-16">Cost</label>
                                            </div>
                                            <div class="col-9 justify-content-end justify-content-md-start col-md-6 pe-0 pe-md-2 d-flex">
                                                <span class="input-group-prepend col-auto">
                                                    <button wire:click.prevent="decreaseAmount" type="button"
                                                        class="btn button-green btn-number"
                                                        :disabled="{{$amount<=0?'true':'false'}}"
                                                    ><svg
                                                            class="icon icon-Minus-Icon" width="24" height="24">
                                                            <use href="{{asset('/img/icons.svg#icon-Minus-Icon')}}"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                                <input type="number" wire:model="amount"
                                                    class="form-control default mx-2 w-50 text-center fw-bold"
                                                    name="Amount"
                                                    id="Amount" min="0" max="100" value="75"
                                                    wire:input.prevent.debounce.1000ms="setBidAmount"
                                                    >
                                                <span class="input-group-append col-auto">
                                                    <button wire:click.prevent="increaseAmount" type="button"
                                                        class="btn button-green btn-number {{$max_limit_amount}}"
                                                        :disabled="{{$amount>=$max_limit_amount?'true':'false'}}"
                                                    >
                                                        <svg class="icon icon-Plus-Icon" width="24" height="24">
                                                            <use href="{{asset('/img/icons.svg#icon-Plus-Icon')}}"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                            </div>
                                        @else
                                            <div class="col-3"><label for="qty" class="fs-16">Quantity</label>
                                            </div>
                                            <div class="col-6 d-flex">
                                                <span class="input-group-prepend col-auto">
                                                    <button wire:click.prevent="decreaseBidUnit" type="button"
                                                            class="btn button-green btn-number"
                                                            :disabled="{{$bid_units<=0?'true':'false'}}"><svg
                                                            class="icon icon-Minus-Icon" width="24" height="24">
                                                            <use href="{{asset('/img/icons.svg#icon-Minus-Icon')}}"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                                <input type="number" wire:model="bid_units"
                                                    class="form-control default mx-2 w-50 text-center fw-bold"
                                                    name="qty"
                                                    id="qty" min="0" max="100" value="75"
                                                    wire:input.prevent.debounce.1000ms="setBidUnit"
                                                    >
                                                <span class="input-group-append col-auto">
                                                    <button wire:click.prevent="increaseBidUnit" type="button"
                                                            class="btn button-green btn-number" :disabled="{{$bid_units>=$total_reamining_units?'true':'false'}}">
                                                        <svg class="icon icon-Plus-Icon" width="24" height="24">
                                                            <use href="{{asset('/img/icons.svg#icon-Plus-Icon')}}"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                            </div>
                                        @endif

                                        <?php /*<div class="col-12 col-md-3 pt-2 pt-md-0 pe-0">
                                        <div class="col-12 col-md-3 pt-2 pt-md-0 pe-0">
                                            <button type="button" class="button-secondary w-100"
                                                    wire:click.prevent="switchBidShowData">
                                                <svg
                                                    class="icon icon-Repeat me-2" width="16" height="16">
                                                    <use href="{{asset('/img/icons.svg#icon-Repeat')}}"></use>
                                                </svg> {{($bidShowData == 'cost') ? 'Units' : 'Cost' }}</button>
                                        </div>*/?>
                                        <x-jet-input-error for="error" class="mt-2"/>
                                        @if($bidShowData=='cost')
                                        <x-jet-input-error for="amount" class="mt-2" />
                                        @else
                                        <x-jet-input-error for="bid_units" class="mt-2" />
                                        @endif
                                        <x-jet-input-error for="rate" class="mt-2" />
                                    </div>

                                    <!-- <div class="col-12 row align-items-center  d-flex pe-0">
                                        <div class="col col-md-4"><label for="Expiration" class="fs-16">Expiration
                                                date</label>
                                        </div>
                                        <div class="col-6 d-flex">
                                            <div class="date-wrap"
                                                wire:click.prevent="toggleDateModal"
                                                data-bs-target="#dateModal"
                                                data-bs-toggle="modal"
                                                data-bs-dismiss="modal">
                                                <input class="form-control default fs-16 fw-bold"
                                                    name="Expiration"
                                                    wire:model="expiration_date"
                                                    id="Expiration"
                                                    data-linked-input="#datepicker-expiration">
                                                <span class="icon">
                                                <svg class="icon icon-calendar" width="16" height="16">
                                                    <use href="{{asset('/img/icons.svg#icon-calendar')}}"></use>
                                                </svg>
                                            </span>
                                            </div>
                                        </div>
                                        <x-jet-input-error for="expiration_date" class="mt-2"/>
                                    </div> -->
                                </div>
                            </div>
                            <div class="col-12 mb-24">
                                <hr class="m-0">
                            </div>
                            <div class="col-12 d-flex justify-content-between mb-20">
                                <div class="col-auto fs-16 d-flex justify-content-center align-items-center">
                                    Total:
                                </div>
                                <div class="col-auto fs-16 d-flex justify-content-center align-items-center">USD
                                    <span
                                        class="ms-2 fw-bold fs-24">{{($activeTab == 'buy') ? price_format($cost) : price_format($amount)}}</span>
                                </div>
                            </div>
                            <div class="row col-12 m-0">
                                <button type="button" class="button-green w-100" wire:click.prevent="openStripeModal"
                                    wire:loading.attr="disabled">
                                        {{$activeTab == 'buy'?'Buy':'Send Bid'}}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-jet-modal>
    @livewire('buy.stripe-modal')
</div>
