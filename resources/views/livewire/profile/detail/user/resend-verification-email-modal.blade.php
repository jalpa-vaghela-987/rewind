<div>
    <x-jet-dialog-modal wire:model="showModal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4">
                <div class="modal-body p-0 row">
                    <div class="row col-12 p-0 m-0">
                    <x-slot name="title">
                        <h5 class="black-color col fw-bold mb-4">
                            Resend
                            <button type="button" class="btn-close opacity-1 float-right align-top align-self-start ms-auto col-auto me-2" wire:click.prevent="openCloseResendVerificationEmail" wire:loading.attr="disabled" aria-label="Close"></button>
                        </h5>
                    </x-slot>

                    <x-slot name="content">
                        <div class="row col-12 p-0 mx-0 mb-24">
                            <p class="text-left text-secondary fs-16">REWIND activities require you to validate your email via mail you receive during the registration process. Plesae press the "resend" button if you can not find it</p>
                        </div>
                        <div class="col-12 d-flex flex-column justify-content-end mt-2">
                            <div class="col-12 d-flex flex-column justify-content-end mt-2">
                                <a class="button-green w-100" href="javascript:void(0);" wire:click="reSendValidationLink()" wire:loading.attr="disabled">{{ __('Re-Send Validation Email') }}</a>
                            </div>
                        </div>
                    </x-slot>
                    <x-slot name="footer">
                    </x-slot>
                    </div>
                </div>
            </div>
        </div>
    </x-jet-dialog-modal>

</div>
