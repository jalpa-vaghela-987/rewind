<div>
    <x-jet-dialog-modal wire:model="showLoginModal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4">
                <div class="modal-body p-0 row">
                    <div class="row col-12 p-0 m-0">
                        <x-slot name="title">
                            <h5 class="black-color col fw-bold mb-4">
                                Registration required
                                <button type="button" class="btn-close opacity-1 float-right align-top align-self-start ms-auto col-auto me-2" x-on:click="$wire.emit('openCloseRestrictionModal')" aria-label="Close"></button>
                            </h5>
                        </x-slot>

                        <x-slot name="content">
                            <div class="row col-12 p-0 m-0 mb-3">
                                <p class="text-left text-secondary fs-16 lh-base">
                                  {{config('app.name')}} platform offers a number of features that you can
                                    enjoy by registering.<br>
                                    If you already have an account, please log in.</p>
                            </div>
                            <div class="row col-12 m-0">
                                <a class="button-secondary col"
                                   href="{{route('login')}}">Log in</a>

                                <a class="button-green ms-2 col" href="{{route('register')}}">Register</a>
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
