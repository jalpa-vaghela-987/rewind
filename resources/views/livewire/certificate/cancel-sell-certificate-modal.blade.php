<div>
    <x-jet-dialog-modal wire:model="showModal" data-backdrop="static" data-keyboard="false" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content p-4">
                <div class="modal-body p-0 row">
                    <x-slot name="title">
                        <div class="row col-12 p-0 m-0">
                            <h5 class="black-color col fw-bold mb-2">
                                Cancel Sell
                            </h5>
                            <button type="button"
                                class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2"
                                wire:click.prevent="closeCancelSellCertificateModal" wire:loading.attr="disabled" aria-label="Close"></button>
                        </div>
                    </x-slot>

                    <x-slot name="content">
                        <div class="row col-12 p-0 m-0 mb-3">
                            <p class="text-left text-secondary fs-16 lh-base">This Certificate is about to be removed from your “On Sell” list.
                                The status of this product will be changed back to "Approved" for your next sell request.
                                Would you like to proceed?</p>
                        </div>


                        <div class="row col-12 m-0">
                            <a class="button-secondary col" wire:click.prevent="closeCancelSellCertificateModal" wire:loading.attr="disabled" aria-label="Close"
                                href="javascrip:void(0);">Cancel</a>
                            <a class="button-green ms-2 col" href="javascrip:void(0);" wire:click.prevent="deleteCancelSellCertificate()" wire:loading.attr="disabled">Cancel Sell </a>
                        </div>
                    </x-slot>

                    <x-slot name="footer">
                    </x-slot>
                </div>
            </div>
        </div>
    </x-jet-dialog-modal>
</div>
