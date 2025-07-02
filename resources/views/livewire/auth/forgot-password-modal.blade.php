<div>
    <x-jet-dialog-modal wire:model="showModal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4">
                <div class="modal-body p-0 row">
                    <x-slot name="title">
                        <div class="row col-12 p-0 m-0">
                            <h5 class="black-color col fw-bold mb-2">
                                Forgot Your Password?
                            </h5>
                            <button wire:click.prevent="openCloseForgotPasswordModal()" type="button" class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2"></button>
                        </div>
                    </x-slot>
                    <x-slot name="content">
                        <div class="row col-12 p-0 m-0 mb-3">
                            @if($success != null)
                                <p class="mb-4 font-medium text-sm text-green-600">{{$success}}</p>
                            @endif
                            @if($error != null)
                                <p class="mb-4 font-medium text-sm text-red-600">{{$error}}</p>
                            @endif
                            <p class="text-left text-secondary fs-16 lh-base">Enter your email address and we’ll send you a
                                link to reset
                                your password</p>
                        </div>
                        @if (session('status'))
                            <div class="mb-4 font-medium text-sm text-green-600">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form method="POST" wire:submit.prevent="sendResetPasswordLink()">
                            <div class="row col-12 p-0 m-0">
                                @csrf
                                <div class="col-12 mb-20">
                                    <input type="email" class="form-control default {{$errors->has('email')?'error':''}}" id="email" wire:model.defer="email" autocomplete="email">
                                    <x-jet-input-error for="email" class="error-message mt-2 d-flex align-items-center" />
                                </div>
                            </div>
                            <div class="row col-12 m-0">
                                <button type="button" class="button-secondary col" wire:click.prevent="openCloseForgotPasswordModal()">Cancel</button>
                                <button type="button" wire:click.prevent="sendResetPasswordLink()" class="button-green ms-2 col" wire:loading.attr="disabled">Reset Password</button>
                            </div>
                        </form>
                    </x-slot>

                    <x-slot name="footer">
                    </x-slot>
                </div>
            </div>
        </div>
    </x-jet-dialog-modal>
</div>
