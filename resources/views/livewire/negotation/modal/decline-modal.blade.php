<div>
    <x-jet-modal class="modal fade" id="declineModal" tabindex="-1" wire:model="declineModal">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content p-4">
                <div class="modal-body p-0 row">
                    <div class="row col-12 p-0 m-0">
                        <h4 class="black-color col fw-bold mb-2">
                            Confirm Your reply
                        </h4>
                        <button type="button"
                                class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2"
                                wire:click="closeModal"></button>
                    </div>
                    <div class="row col-12 p-0 m-0 mb-3">
                        <p class="text-left text-secondary fs-16 lh-base">
                            You are about to send the buyer a Decline reply. The buyer
                            will be notified of your
                            decision, and can optionally send
                            you a revised offer. Do You want to proceed?
                        </p>
                    </div>
                    <div class="row col-12 m-0">

                        <a class="button-secondary col" wire:click="closeModal"
                           href="javascript:void(0);">Cancel</a>
                        <button class="button-green ms-2 col" wire:click.prevent="declineOffer" {{ $isDisabled ? 'disabled' : '' }}>Confirm Reply</button>
                    </div>
                </div>
            </div>
        </div>
    </x-jet-modal>
</div>
