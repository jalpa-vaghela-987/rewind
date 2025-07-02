<div>
    <x-jet-modal class="modal fade" id="offeredModal" tabindex="-1" wire:model="offeredModal">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content p-4">
                @if($quantity <= $selectedBid->sell_certificate->remaining_units)
                    <div class="modal-body p-0 row">
                        <div class="row col-12 p-0 m-0">
                            <h4 class="black-color col fw-bold mb-2">
                                Verify New Offer
                            </h4>
                            <button type="button"
                                    class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2"
                                    wire:click="closeModal"></button>
                        </div>
                        <div class="row col-12 p-0 m-0 mb-3">
                            <p class="text-left text-secondary fs-16 lh-base">
                                Please verify the details of your new offer below:<br>
                                <b> Certificate Name:</b> {{$selectedBid->certificate->name}}<br>
                                <b>Price:</b> ${{$price}}<br>
                                <b>Quantity:</b> {{$quantity}}
                            </p>
                        </div>
                        <div class="row col-12 m-0">
                            <a class="button-secondary col" wire:click="closeModal"
                               href="javascript:void(0);">Cancel</a>
                            <a class="button-green ms-2 col" href="javascript:void(0);" wire:click.prevent="acceptCounterOffer" {{ $isDisabled ? 'disabled' : '' }}>Verify Offer</a>
                        </div>
                    </div>
                @else
                    <div class="row col-12 p-0 m-0">
                        <h4 class="black-color col fw-bold mb-2 p-0">Maximum Quantity</h4>
                        <button type="button"
                                class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2"
                                wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body p-0 row">
                        <div class="row col-12 p-0 m-0 mb-3">
                            <p class="text-left text-secondary fs-16 lh-base text-danger">
                                @if(($selectedBid->sell_certificate->remaining_units == 0) || (($selectedBid->currentCounterOffer) && ($selectedBid->currentCounterOffer->quantity == 0)))
                                    Quantity is not available you need to decline this Offer!!!
                                @else
                                    Now available quantity is : {{$selectedBid->sell_certificate->remaining_units}}.
                                    </br>
                                    You need to add offer quantity less then or equal available initial quantity.
                                @endif
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </x-jet-modal>
</div>
