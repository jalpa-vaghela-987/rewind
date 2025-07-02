

<x-jet-dialog-modal wire:model.defer="showModal" id="logoutModal" data-backdrop="static" data-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
                <div class="modal-content p-4">
                        <div class="modal-body p-0 row">
                                <x-slot name="title">
                                        <div class="row col-12 p-0 m-0">
                                                <h5 class="black-color col fw-bold mb-2">
                                                        Log Out
                                                </h5>
                                                <button type="button"
                                                        class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2"
                                                        wire:click.prevent="openCloseLogoutModal"
                                                        aria-label="Close"
                                                        wire:loading.attr="disabled">
                                                </button>
                                        </div>
                                </x-slot>
                                <x-slot name="content">
                                        <div class="row col-12 p-0 m-0 mb-3">
                                                <p class="text-left text-secondary fs-16 lh-base">You are about to log out of your account.<br>
                                                        Are you sure you want to continue?</p>
                                        </div>
                                        <div class="row col-12 m-0">
                                                <a class="button-secondary col" aria-label="Close"
                                                href="javascript:void(0);" wire:click.prevent="openCloseLogoutModal">Cancel</a>
                                                <a class="button-green ms-2 col" href="javascript:void(0);" wire:click="logoutUser"
                                                wire:loading.attr="disabled">Log Out</a>
                                        </div>
                                </x-slot>
                                <x-slot name="footer">
                                </x-slot>
                        </div>
                </div>
        </div>
</x-jet-dialog-modal>
