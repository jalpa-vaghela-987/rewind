<div>
    <x-jet-modal class="modal fade" id="cancelledModal" tabindex="-1" wire:model="cancelledModal">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content p-4">
                @if(($selectedBid->sell_certificate->remaining_units == 0 && $selectedBid->status != 4) || (($selectedBid->currentCounterOffer) && ($selectedBid->currentCounterOffer->quantity == 0) && $selectedBid->currentCounterOffer->status != 4))
                    <div class="row col-12 p-0 m-0">
                        <h4 class="black-color col fw-bold mb-2 p-0">Insufficient Quantity</h4>
                        <button type="button"
                                class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2"
                                wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body p-0 row">
                        <div class="row col-12 p-0 m-0 mb-3">
                            <p class="text-left text-secondary fs-16 lh-base text-danger">
                                Quantity is not available you need to decline this Offer!!!
                            </p>
                        </div>
                    </div>
                @else
                    <div class="modal-body p-0 row">
                            <div class="row col-12 p-0 m-0">
                                <h4 class="black-color col fw-bold mb-2">
                                    Cancel Your Sell
                                </h4>
                                <button type="button"
                                        class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2"
                                        wire:click="closeModal"></button>
                            </div>
                            <div class="row col-12 p-0 m-0 mb-3">
                                <p class="text-left text-secondary fs-16 lh-base">
                                    You are about to send the buyer a cancel sell reply. The
                                    buyer will be notified of your
                                    decision and will not be able to
                                    proceed with this deal.The status of this product will be
                                    changed back to "Approved" for
                                    your next sell request. Would
                                    you like to proceed?
                                </p>
                            </div>
                            <div class="row col-12 m-0">
                                <a href="javascript:void(0);" class="button-secondary col" wire:click.prevent="closeModal">Cancel</a>
                                <a class="button-green ms-2 col" href="javascript:void(0);" wire:click.prevent="cancelOffer" {{ $isDisabled ? 'disabled' : '' }}>Cancel Sell</a>
                            </div>
                        </div>
                @endif
            </div>
        </div>
    </x-jet-modal>
</div>
