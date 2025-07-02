<div>
    <x-jet-dialog-modal wire:model="showModal" data-backdrop="static" data-keyboard="false" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4">
                <div class="modal-body p-0 row">
                    <x-slot name="title">
                        <div class="row col-12 p-0 m-0">
                            <h5 class="black-color col fw-bold mb-3">
                                Change Phone Number
                            </h5>
                            <button type="button"
                                class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2"
                                wire:click.prevent="closeChangePhoneModal" aria-label="Close"></button>
                        </div>
                    </x-slot>
                    <x-slot name="content">
                        <div class="row col-12 p-0 mx-0 mb-24">
                            <p class="text-left text-secondary fs-16">Your phone number will need to be validated again once
                                you change it. To proceed, please enter a new phone number:</p>
                        </div>
                        <div class="row col-12 p-0 m-0">
                            <div class="row mb-24">
                                <label for="Phone" class="form-label p-0 black-color">New Phone Number</label>
                                <select class="form-control default selectpicker w-25 me-2 {{$errors->has('phone_prefix')?'error':''}}"  id="phone_prefix" title=" "  wire:model.defer="phone_prefix" data-live-search="true">
                                    <option value=""> </option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->phone_prefix }}">+{{ $country->phone_prefix }}</option>
                                    @endforeach
                                </select>
                                <input type="number" wire:model.defer="phone" onKeyPress="if(this.value.length==10) return false"  class="form-control default col {{$errors->has('phone')?'error':''}}" id="phone">
                                <x-jet-input-error for="phone" class="mt-2" />
                                <x-jet-input-error for="phone_prefix" class="mt-2" />
                            </div>
                            <div class="col-12 d-flex flex-column justify-content-end mt-2">
                                <a class="button-green w-100 save-phone-btn" href="javascript:void(0);" wire:click.prevent="savePhone()">Send Validation SMS</a>
                            </div>
                        </div>
                    </x-slot>
                    <x-slot name="footer">
                    </x-slot>
                </div>
            </div>
        </div>
    </x-jet-dialog-modal>
</div>

@push('scripts')
    <script>
        document.addEventListener("livewire:load", function (event) {
            $('select.selectpicker').selectpicker('destroy');

            Livewire.hook('message.processed', () => {
                $('.save-phone-btn').click(function () {
                    $('select.selectpicker').selectpicker('destroy');
                })
            });
        });
    </script>
@endpush
