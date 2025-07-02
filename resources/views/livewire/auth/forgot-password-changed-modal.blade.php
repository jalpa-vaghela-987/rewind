<div>
    <x-jet-dialog-modal wire:model="showModal" data-backdrop="static" data-keyboard="false">
        <x-slot name="title">
            <div class="row col-12 p-0 m-0">
                <h5 class="black-color col fw-bold mb-2">
                    Password Changed!
                </h5>
                <button wire:click.prevent="openCloseForgotPasswordChangedModal()" type="button"
                        class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2"></button>
            </div>
            <div class="row col-12 p-0 mx-0 mb-24">
                <p class="text-left text-secondary fs-16 lh-base">You’ll be redirected to the dashboard section
                </p>
            </div>
        </x-slot>

        <x-slot name="content">
            <form method="post">
                <div class="row col-12 m-0">
                    <button type="button" class="button-green col" wire:click.prevent="openCloseForgotPasswordChangedModal()">Close</button>
                </div>
            </form>
        </x-slot>

        <x-slot name="footer">
        </x-slot>
    </x-jet-dialog-modal>
</div>
