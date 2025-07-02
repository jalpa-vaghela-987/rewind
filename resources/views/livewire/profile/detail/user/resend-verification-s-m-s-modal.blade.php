<div>
    <x-jet-dialog-modal wire:model="showModal" data-backdrop="static" data-keyboard="false" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4">
                <div class="modal-body p-0 row">
                        <x-slot name="title">
                            <div class="row col-12 p-0 m-0">
                                <h4 class="black-color col fw-bold mb-2">
                                    Validate Your Phone Number
                                </h4>
                                <button type="button"
                                    class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2" wire:click.prevent="openCloseResendVerificationSMS" aria-label="Close"></button>
                            </div>
                        </x-slot>

                        <x-slot name="content">
                            <div class="row col-12 p-0 m-0 mb-3">
                                <p class="text-left text-secondary fs-16 lh-base">REWIND activities require you to validate your phone number via the SMS message you receive during the registration process. Please press the "resend" button if you cannot find it.</p>
                            </div>
                            <div class="row col-12 m-0">
                                <a class="button-green w-100 button-send" href="javascript:void(0);" wire:click="reSendValidationLink()" wire:loading.attr="disabled">Re-Send Validation SMS Message</a>
                            </div>
                        </x-slot>
                        <x-slot name="footer">
                        </x-slot>
                </div>
            </div>
        </div>
    </x-jet-dialog-modal>
</div>
