<div>
    <x-jet-modal class="modal fade" wire:model="showSellCertificateModal">
        <div class="modal-content p-32">
            <div class="modal-body p-0 row">
                <div class="row col-12 p-0 m-0">
                    <h5 class="black-color col fw-bold mb-20">
                        <svg class="icon icon-Forest-ERB me-2" width="32" height="32">
                            <use
                                href="{{asset('img/icons.svg#'. ($sellCertificate ? $sellCertificate->certificate->project_type->image_icon : null))}}"></use>
                        </svg>
                        Sell {{ $sellCertificate ? $sellCertificate->certificate->project_type->type : null}}
                    </h5>
                    <button type="button"
                            class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2"
                            wire:click="closeSellModal"></button>
                    <div class="col-12 d-flex align-items-baseline mb-20">
                        <h5 class="price fw-bold me-3">${{price_format($amount)}}</h5>
                        {{--                                        <span class="statistic-price statistic-increase d-flex align-items-center fs-12">--}}
                        {{--                                <svg class="icon icon-triangle-top me-1" width="8" height="8">--}}
                        {{--                                    <use href="./img/icons.svg#icon-triangle-top"></use>--}}
                        {{--                                </svg>{{number_format($valueDiff,2)}} (--}}
                        {{--                                --}}{{--@if($differenceType == 'inc') + @else - @endif--}}{{-- {{number_format($priceDifference,2)}})--}}
                        {{--                            </span>--}}
                    </div>

                </div>
                <div class="row col-12 mx-0 black-10 mb-24">
                    <hr class="m-0">
                </div>
                <form class="row col-12 p-0 m-0">
                    <div class="col-12 mb-24 row d-flex align-items-center">
                        <div class="col-3"><label for="pricePerUnit" class="fs-16">Price Per Unit</label></div>
                        <div class="col-9 d-flex">
									<span class="input-group-prepend col-auto">
										<button type="button" class="btn button-green btn-number1"
                                                wire:click="decrease('pricePerUnit')" data-type="minus"
                                                data-field="pricePerUnit"><svg class="icon icon-Minus-Icon" width="24"
                                                                               height="24">
												<use href="{{asset('img/icons.svg#icon-Minus-Icon')}}"></use>
											</svg>
										</button>
									</span>
                            <input type="number" wire:model="pricePerUnit"
                                   class="form-control default mx-2 w-50 text-center fw-bold"
                                   name="pricePerUnit"
                                   id="pricePerUnit">
                            <span class="input-group-append col-auto">
										<button type="button" class="btn button-green btn-number1"
                                                data-type="plus" wire:click="increase('pricePerUnit')"
                                                data-field="pricePerUnit">
											<svg class="icon icon-Plus-Icon" width="24" height="24">
												<use href="{{asset('img/icons.svg#icon-Plus-Icon')}}"></use>
											</svg>
										</button>
									</span>
                        </div>
                    </div>
                    <div class="col-12 mb-24 row d-flex align-items-center">
                        <div class="col-3"><label for="QTY" class="fs-16">QTY</label></div>
                        <div class="col-9 d-flex">
									<span class="input-group-prepend col-auto">
										<button type="button" class="btn button-green btn-number1"
                                                wire:click="decreaseAmount" data-type="minus"
                                                data-field="QTY"><svg class="icon icon-Minus-Icon" width="24"
                                                                      height="24">
												<use href="{{asset('img/icons.svg#icon-Minus-Icon')}}"></use>
											</svg>
										</button>
									</span>
                            <input type="text" wire:model="unit" wire:change="unitChange"
                                   class="form-control default mx-2 w-50 text-center fw-bold" name="QTY" id="QTY"
                                   min="0" max="100" value="75">
                            <span class="input-group-append col-auto">
										<button type="button" class="btn button-green btn-number1"
                                                data-type="plus" wire:click="increaseAmount" data-field="QTY">
											<svg class="icon icon-Plus-Icon" width="24" height="24">
												<use href="{{asset('img/icons.svg#icon-Plus-Icon')}}"></use>
											</svg>
										</button>
									</span>
                        </div>
                    </div>
                    <div class="col-12 mb-24">
                        <x-jet-input-error for="error" class="mt-2"/>
                        <hr class="m-0">
                    </div>

                    <div class="col-12 d-flex justify-content-between mb-20">
                        <div class="col-auto fs-16 d-flex justify-content-center align-items-center">Total:</div>
                        <div class="col-auto fs-16 d-flex justify-content-center align-items-center">
                            USD <span class="ms-2 fw-bold fs-24">${{price_format($total)}}</span>
                        </div>
                    </div>
                    @if(!$bank)
                        <div class="col-12 mb-24">
                            <hr class="m-0">
                        </div>
                        <div class="col-12 d-flex justify-content-between mb-20">
                            <div class="col-auto fs-16 d-flex justify-content-center align-items-center">
                                Your Bank Details is missing
                            </div>
                            <div class="col-auto fs-16 d-flex justify-content-center align-items-center">
                                <a class="button-green w-100"
                                   wire:click="$emit('openCloseBankFormModal')"
                                   href="javascript:void(0)">
                                    Add Bank Account
                                </a>
                            </div>
                        </div>
                        <div class="row col-12 m-0">
                            <a class="button-green w-100" wire:click="sellCertificate"
                               href="javascript:void(0)" disabled>
                                Sell
                            </a>
                        </div>
                    @else
                        <div class="row col-12 m-0">
                            <a class="button-green w-100" @if($unit <= $maxQuantity)  wire:click="sellCertificate" @else disabled @endif
                               href="javascript:void(0)">
                                Sell
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </x-jet-modal>
</div>
