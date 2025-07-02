<div>
    <!-- Card Modal -->
    <x-jet-modal class="modal fade" wire:model="showModal">
        <div class="modal-content p-32">
            <div class="modal-body p-0 row">
                <div class="row col-12 p-0 m-0">
                    <h5 class="black-color col-7 fw-bold mb-20">
                        {{$title}} Role
                    </h5>
                    <button type="button"
                            wire:click.prevent="closeRoleModal"
                            class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2"></button>
                </div>

                <form  class="row col-12 p-0 m-0">
                    <div class="col-12 mb-20">
                        <label for="Name" class="form-label p-0 black-color">Name</label>
                        <input type="text" id="name" class="form-control default" id="Name"
                               placeholder="Role Name" wire:model.defer="name">
                        <x-jet-input-error for="name" class="mt-2"/>
                    </div>
                    <div class="col-12 mb-20">
                        <label for="Name" class="form-label p-0 black-color">Permissions</label>
                        @foreach($permissions->chunk(count($permissions) / 3) as $chunk)
                            <div class="col-sm-4">
                                @foreach ($chunk as $value)
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input  wire:model="role_permissions" class="custom-control-input" type="checkbox" id="customCheckbox{{ $value->id }}" value="{{ $value->id }}">
                                        <label for="customCheckbox{{ $value->id }}" class="custom-control-label">{{ $value->name }}</label><br>
                                    </div>
                                @endforeach

                            </div>
                        @endforeach
                        <x-jet-input-error for="name" class="mt-2"/>
                    </div>
                    <div class="col-12 d-flex flex-column justify-content-end mt-2">
                        <x-secondary-link-button class="button-green w-100 button-send fw-normal mb-40"
                                                 wire:click="saveRole()" wire:loading.attr="disabled">
                            {{ __('Save') }}
                        </x-secondary-link-button>

                    </div>
                </form>

            </div>
        </div>
    </x-jet-modal>

</div>
