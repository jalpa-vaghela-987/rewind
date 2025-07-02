<div>
    <x-jet-modal class="modal fade" id="acceptModal" tabindex="-1" wire:model="acceptModal">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content p-4">
                @php $flag = 1; @endphp
                @if($selectedBid->currentCounterOffer)
                    @if($selectedBid->currentCounterOffer->quantity >  $selectedBid->sell_certificate->remaining_units)
                        @php $flag = 0; @endphp
                    @endif
                @elseif($selectedBid->unit > $selectedBid->sell_certificate->remaining_units)
                    @php $flag = 0; @endphp
                @endif

                @if($flag == 0)
                    <div class="row col-12 p-0 m-0">
                        <h4 class="black-color col fw-bold mb-2 p-0">Insufficient Quantity</h4>
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
                                    Quantity is not able to accept you need to decline or add new counter offer for available quantity.
                                @endif
                            </p>
                        </div>
                    </div>
                @else
                    <div class="modal-body p-0 row">
                        <div class="row col-12 p-0 m-0">
                            <h4 class="black-color col fw-bold mb-2">
                                Verify Your Deal
                            </h4>
                            <button type="button"
                                    class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2"
                                    wire:click="closeModal"></button>
                        </div>
                        <div class="row col-12 p-0 m-0 mb-3">
                            <p class="text-left text-secondary fs-16 lh-base">
                                Congratulations! You have successfully closed a deal. Before
                                a final approval, please verify
                                the details of your deal
                                below:<br>
                                <b>Certificate Name:</b> {{$selectedBid->certificate->name}}<br>
                                @if($counterOfferId != null && $selectedBid->currentCounterOffer)
                                    <b>Price:</b> ${{($selectedBid->currentCounterOffer->amount/ $selectedBid->currentCounterOffer->quantity)}}<br>
                                    <b>Quantity:</b> {{$selectedBid->currentCounterOffer->quantity}}
                                @else
                                    <b>Price:</b> ${{$selectedBid->rate}}<br>
                                    <b>Quantity:</b> {{$selectedBid->unit}}
                                @endif
                            </p>

                            <p class="text-left text-secondary fs-16 lh-base mt-3">
                                By clicking the "Verify Deal" button, you confirm that you
                                have read and understand the
                                terms and conditions of this
                                deal and that you agree to abide by them. If you have any
                                questions or concerns, please
                                contact our customer support
                                team.
                            </p>
                        </div>
                        <div class="row col-12 m-0">

                            <a href="javascript:void(0);" class="button-secondary col" wire:click="closeModal">Cancel</a>
                            <a class="button-green ms-2 col" href="javascript:void(0);" wire:click.prevent="acceptOffer" {{ $isDisabled ? 'disabled' : '' }}>Verify Deal</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </x-jet-modal>
</div>
