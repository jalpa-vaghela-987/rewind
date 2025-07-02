<div>
<x-jet-dialog-modal wire:model.defer="showModal" id="profilePictureModalCrop" tabindex="-1" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4">
                <div class="modal-body p-0 row">
                    <x-slot name="title">
                        <div class="row col-12 p-0 m-0">
                            <h5 class="black-color col fw-bold mb-3">
                                Change Profile Picture
                            </h5>
                            <button type="button"
                                class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2"
                                wire:click.prevent="closeProfilePictureModal"
                                aria-label="Close"
                            >
                            </button>
                        </div>
                    </x-slot>
                </div>
                <x-slot name="content">
                    <form class="row col-12 p-0 m-0">
                        <div class="row mx-0 mb-24">
                            <label for="fileElem" class="form-label p-0 black-color">Upload New Profile Picture</label>
                            <label class="drop-area drop-area-img-upload open-cropper">
                                <span><span class="info">
                                        <svg class="icon icon-upload-cloud" width="32" height="32">
                                            <use href="{{asset('img/icons.svg#icon-upload-cloud')}}"></use>
                                        </svg>
                                        <b>upload a file </b>or drag and
                                        drop</span>
                                    <span class="limit">PNG, JPG, GIF up to 10MB</span>
                                </span>
                                <input type="file" class="fileElem" id="fileElem" accept="image/*" style="display:none;"/>
                                <span id="gallery">
                                </span>
                            </label>
                            @error('profile_photo') <span class="error">{{ $message }}</span> @enderror
                            <div id="container-crop" class="visually-hidden"></div>
                        </div>
                        <div class="col-12 d-flex flex-column justify-content-end">
                            <a class="button-green w-100 crop-image-finish disabled" href="javascript:void(0);">Crop and Upload</a>
                        </div>
                    </form>
                </x-slot>
            </div>
        </div>

        <x-slot name="footer">
        </x-slot>
    </x-jet-dialog-modal>
</div>
