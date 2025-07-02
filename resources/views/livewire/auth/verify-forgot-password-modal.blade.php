<div>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4">
            <div class="modal-body p-0 row">
                <x-jet-dialog-modal wire:model="showModal" data-backdrop="static" data-keyboard="false">
                    <x-slot name="title">
                        <div class="row col-12 p-0 m-0">
                            <h5 class="black-color col fw-bold mb-2">
                                Reset Your Password
                            </h5>
                            <button wire:click.prevent="openCloseVerifyForgotPasswordModal()" type="button"
                                    class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2"></button>
                        </div>
                        <div class="row col-12 p-0 m-0 mb-3">
                            <p class="text-left text-secondary fs-16 lh-base">Enter your email address and we’ll send you a
                                link to reset
                                your password</p>
                        </div>
                    </x-slot>
                    <x-slot name="content">
                        @if (session('status'))
                            <div class="mb-4 font-medium text-sm text-green-600">
                                {{ session('status') }}
                            </div>
                        @endif
                            @if (session('error'))
                                <div class="mb-4 font-medium text-sm text-red-600">
                                    {{ session('error') }}
                                </div>
                            @endif

                        <form class="row col-12 p-0 m-0" method="POST">
                            <div class="row col-12 m-0 mb-3">
                                <label for="password" class="form-label p-0 black-color">New Password</label>
                                <div class="position-relative p-0">
                                    <input type="password" class="form-control default" id="new_password" wire:model.defer="new_password" placeholder=" "/>
                                    <span toggle="#new_password" class="field-icon toggle-password">
                                        <svg class="icon icon-Eye" width="24" height="24">
                                            <use href="{{asset('img/icons.svg#icon-Eye')}}"></use>
                                        </svg>
                                        <svg class="icon icon-Eye-off" width="24" height="24">
                                            <use href="{{asset('img/icons.svg#icon-Eye-off')}}"></use>>
                                        </svg>
                                    </span>
                                </div>
                                <x-jet-input-error for="new_password" class="error-message mt-2 d-flex align-items-center" />
                            </div>
                            <div class="row col-12 m-0 mb-3">
                                <label for="password2" class="form-label p-0 black-color">Confirm Password</label>
                                <div class="position-relative p-0">
                                    <input type="password" class="form-control default" id="confirm_password2" wire:model.defer="confirm_password" placeholder=" "/>
                                    <span toggle="#confirm_password2" class="field-icon toggle-password">
                                        <svg class="icon icon-Eye" width="24" height="24">
                                            <use href="{{asset('img/icons.svg#icon-Eye')}}"></use>
                                        </svg>
                                        <svg class="icon icon-Eye-off" width="24" height="24">
                                            <use href="{{asset('img/icons.svg#icon-Eye-off')}}"></use>
                                        </svg>
                                    </span>
                                </div>
                                <x-jet-input-error for="confirm_password" class="error-message mt-2 d-flex align-items-center" />
                            </div>
                            <div class="row col-12 m-0">
                                <button class="button-green col" wire:click.prevent="forgotPassword()">Set Password</button>
                            </div>
                        </form>
                    </x-slot>
                    <x-slot name="footer">
                    </x-slot>
                </x-jet-dialog-modal>
            </div>
        </div>
    </div>
</div>
