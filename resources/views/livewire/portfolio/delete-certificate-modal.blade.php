<div>
    <x-jet-dialog-modal wire:model="showModal" data-backdrop="static" data-keyboard="false" id="deleteModal"
                        tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content p-4">
                <div class="modal-body p-0 row">
                    <x-slot name="title">
                        <div class="row col-12 p-0 m-0">
                            <h5 class="black-color col fw-bold mb-2">
                                Offset Carbon Credit
                            </h5>
                        </div>
                    </x-slot>

                    <x-slot name="content">
                        <div class="row col-12 p-0 m-0 mb-3">
                            <p class="text-left text-secondary fs-16 lh-base">
                                Your carbon credits will be removed from {{config('app.name')}} and your offset will be
                                registered in your account name
                            </p>
                        </div>

                        <div class="row col-12 p-0 m-0 mb-3">
                            <label  class="remember-label d-flex align-items-center">{{ __('By clicking retire you are agreeing to the terms and conditions.') }}</label>
                        </div>
                        <div class="row col-12 m-0">
                            <a class="button-secondary col"
                               wire:click.prevent="closeDeleteCertificateModal" wire:loading.attr="disabled"
                               aria-label="Close"
                               href="javascrip:void(0);">Cancel</a>
                            <a class="button-green ms-2 col" href="javascrip:void(0);"
                               wire:click.prevent="deleteCertificate()" wire:loading.attr="disabled">Retire</a>
                        </div>
                    </x-slot>

                    <x-slot name="footer">
                    </x-slot>
                </div>
            </div>
        </div>
    </x-jet-dialog-modal>
</div>
