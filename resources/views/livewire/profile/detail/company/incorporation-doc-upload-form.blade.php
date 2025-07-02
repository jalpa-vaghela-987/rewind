<div>
    <x-jet-dialog-modal wire:model="showModal" data-backdrop="static" data-keyboard="false" id="incorporationDocumentModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-4">
                <div class="modal-body p-0 row">
                    <x-slot name="title">
                        <div class="row col-12 p-0 m-0">
                            <h5 class="black-color col fw-bold mb-3">
                                Change Incorporation Document
                            </h5>
                            <button type="button"
                                class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2" wire:click.prevent="openCloseIncorporationDocModal" aria-label="Close"></button>
                        </div>
                    </x-slot>
                    <x-slot name="content">
                        <div class="row col-12 p-0 m-0">
                            <div class="row mx-0 mb-24">
                                <label class="form-label p-0 black-color">Upload Incorporation
                                    Documents</label>
                                <label class="drop-area {{ !$errors->has('incorporation_doc')?(($incorporation_doc)?'full drop-area-cover':''):'' }}">
                                    <span>
                                        <span class="info">
                                            <svg class="icon icon-upload-cloud" width="32" height="32">
                                                <use href="{{asset('img/icons.svg#icon-upload-cloud')}}"></use>
                                            </svg>
                                            <b>upload a file </b>or drag and
                                            drop
                                        </span>
                                        <span class="limit">PNG, JPG, GIF up to 10MB</span>
                                    </span>
                                    <input type="file" wire:model.defer="incorporation_doc" class="incorporateDoc" id="fileElem" accept="file/*">
                                    <span class="gallery">
                                        @if (!$errors->has('incorporation_doc'))
                                            @if(!$is_incorporation_doc_img && !empty($incorporation_doc) && in_array($file_extension,$fileMime))
                                                <embed src="{{$incorporation_doc->temporaryUrl()}}"/>
                                            @elseif($incorporation_doc && in_array($file_extension,$imageMimes))
                                                <img src="{{ $incorporation_doc->temporaryUrl() }}" alt="Prview">
                                            @endif
                                        @endif
                                    </span>
                                </label>
                                <x-jet-input-error for="incorporation_doc" class="mt-2" />
                            </div>
                            <div class="col-12 d-flex flex-column justify-content-end">
                                <a class="button-green w-100" href="javascript:void(0);" wire:click.prevent="save()">Upload Incorporation Doc</a>
                            </div>
                        </div>
                    </x-slot>
                </div>
            </div>
        </div>
    </x-jet-dialog-modal>
</div>
