<div>
    <x-jet-modal class="modal fade" wire:model="showModal" >
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content  p-32">
                <div class="modal-body p-0 row">
                    <div class="row col-12 p-0 m-0">
                        <h5 class="black-color col fw-bold mb-20">
                            Set price alert
                        </h5>
                        <button wire:click.prevent="closeModal" type="button"
                                class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2" ></button>
                        <div class="col-12 d-flex align-items-center mb-20">
                            <svg class="icon icon-Forest-ERB me-2" width="32" height="32">
                                <use
                                    href="{{asset('img/icons.svg#'.@$selectedCertificate->certificate->project_type->image_icon)}}"></use>
                            </svg>
                            <div class="info-main d-flex flex-column">
                                <span class="fw-bold">{{ $this->title }}</span>
                                <span class="d-flex align-items-baseline">
                            <span class="fs-20 fw-bold">${{ price_format($pricePerUnit) }}</span>
                            <?php /*<span
                                class="statistic-price statistic-increase d-flex align-items-center fs-12 ms-3">
                                <svg class="icon icon-triangle-top me-1" width="8" height="8">
                                    <use href="{{asset('/img/icons.svg#icon-triangle-top')}}"></use>
                                </svg>{{$price_difference}} ({{($amount_difference > 0) ? '+' : '-'}}{{$price_average}})
                            </span>*/?>
                        </span>
                            </div>
                        </div>
                    </div>
                    <div class="row col-12 mx-0 black-10 mb-24">
                        <hr class="m-0">
                    </div>
                    <form class="row col-12 p-0 m-0">
                        <div class="col-12">
                            <div class="col-12 row align-items-center mb-24  d-flex px-0 mx-0">
                                @if($priceShowData == 'price')
                                    <div class="col-3"><label for="Amount" class="fs-16">Price</label>
                                    </div>
                                    <div
                                        class="col-9 justify-content-end justify-content-md-start col-md-5 pe-0 pe-md-2 d-flex">
                                                <span class="input-group-prepend col-auto">
                                                    <button wire:click.prevent="decreasePrice('alert_price')" type="button"
                                                            class="btn button-green btn-number"
                                                            data-type="minus" data-field="Amount"><svg
                                                            class="icon icon-Minus-Icon" width="24" height="24">
                                                            <use href="{{asset('/img/icons.svg#icon-Minus-Icon')}}"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                        <input type="number" wire:model="alert_price" wire:change="priceChange($event.target.value)"
                                            class="form-control default mx-2 w-50 text-center fw-bold"
                                            name="Amount"
                                            min="0" max="100" value="75"
                                        >
                                        <span class="input-group-append col-auto">
                                                    <button wire:click.prevent="increasePrice('alert_price')" type="button"
                                                            class="btn button-green btn-number"
                                                            data-type="plus" data-field="Amount">
                                                        <svg class="icon icon-Plus-Icon" width="24" height="24">
                                                            <use href="{{asset('/img/icons.svg#icon-Plus-Icon')}}"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                    </div>
                                @else
                                    <div class="col-3"><label for="Amount" class="fs-16">Percentage</label>
                                    </div>
                                    <div class="col-5 d-flex">
                                                <span class="input-group-prepend col-auto">
                                                    <button wire:click.prevent="decreasePrice('alert_percentage')" type="button"
                                                            class="btn button-green btn-number"
                                                            data-type="minus" data-field="Amount"><svg
                                                            class="icon icon-Minus-Icon" width="24" height="24">
                                                            <use href="{{asset('/img/icons.svg#icon-Minus-Icon')}}"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                        <input type="number" wire:model="alert_percentage" wire:change.prevent="percentageChange($event.target.value)"
                                            class="form-control default mx-2 w-50 text-center fw-bold"
                                            name="Amount"
                                            id="Amount" min="0" max="100" value="75"
                                        >
                                        <span class="input-group-append col-auto">
                                                    <button wire:click.prevent="increasePrice('alert_percentage')" type="button"
                                                            class="btn button-green btn-number"
                                                            data-type="plus" data-field="Amount">
                                                        <svg class="icon icon-Plus-Icon" width="24" height="24">
                                                            <use href="{{asset('/img/icons.svg#icon-Plus-Icon')}}"></use>
                                                        </svg>
                                                    </button>
                                                </span>
                                    </div>
                                @endif
                                <div class="col-12 col-md-4 pt-2 pt-md-0 pe-0">
                                    <button type="button" class="button-secondary w-100"
                                            wire:click.prevent="switchPriceShowData">
                                        <svg
                                            class="icon icon-Repeat me-2" width="16" height="16">
                                            <use href="{{asset('img/icons.svg#icon-Repeat')}}"></use>
                                        </svg>
                                        {{($priceShowData == 'price') ? 'Percentage' : 'Price' }}
                                    </button>
                                </div>
                                <x-jet-input-error for="alert_percentage" class="mt-2" />
                                <x-jet-input-error for="alert_price" class="mt-2"/>
                            </div>
                            <div class="col-12 mb-24">
                                <hr class="m-0">
                            </div>
                            <div class="row col-12 m-0">
                                <button type="button" class="button-green w-100" wire:click.prevent="setAlert"
                                        wire:loading.attr="disabled">Set Alert
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-jet-modal>
</div>
