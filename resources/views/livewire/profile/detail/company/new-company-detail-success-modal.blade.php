<div>
    <x-jet-dialog-modal wire:model="showModal" data-backdrop="static" data-keyboard="false" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4">
                <div class="modal-body p-0">
                    <div class="row">
                        <x-slot name="title">
                            <div class="row col-12 p-0 m-0">
                                <h4 class="black-color col fw-bold mb-2">
                                    Thank you for your time!
                                </h4>
                                <button type="button"
                                    class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2"
                                    wire:click.prevent="hideSuccessModal"
                                    aria-label="Close"></button>
                            </div>
                        </x-slot>

                        <x-slot name="content">
                            <div class="row col-12 p-0 m-0">
                                <div class="row mx-0 mb-24">
                                    <p class="text-left text-secondary fs-16">Our team will update you shortly regarding the approval of company details via email.</p>
                                </div>
                                <div class="col-12 d-flex flex-column justify-content-end">
                                    <a
                                        class="button-green w-100"
                                        href="javascript:void(0);"
                                        wire:click.prevent="hideSuccessModal"
                                        >Close</a>
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
    <!-- Information about company -->
</div>
