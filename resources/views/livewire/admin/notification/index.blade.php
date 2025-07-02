<div class="row col-12 p-0 m-0 h-100">
    <div class="bg-white block-main row m-0 table-container p-0 h-100 pb-32 flex-column">
        <div class="col-12 p-3 row m-0">
            <div class="search-wrap">
                <input type="search" class="base-search w-100" id="table_search" placeholder="search"
                       wire:model="search">
                <span class="icon">
                    <svg class="icon icon-search" width="20" height="20">
                        <use href="{{asset('/img/icons.svg#icon-search')}}"></use>
                    </svg>
                </span>
            </div>
        </div>
        <div class="col-12 p-0 row m-0">
            @if(!empty($lists))
                <div class="row col mb-2 m-4 p-0 mt-0">
                    <h6 class="fw-bold p-0 col-auto">Notifications</h6>
                    <a href="javascript:void(0)" class="button-green message-table ms-auto"
                       wire:click="openFormModal()"
                    >
                        Add New
                    </a>

                </div>

                <x-data-table.infinite-table :model="$lists" :columns="[]" :wantSearching="true" :dateFilter="true">
                    <x-slot name="title">
                    </x-slot>
                    <x-slot name="head">
                        <tr>
                            <th data-filter-control-placeholder="Name"
                                data-field="Sender" data-sortable="true">
                                {{ __('Sender') }}</th>
                            <th data-filter-control-placeholder="Receiver"
                                data-field="Receiver" data-sortable="true">
                                {{ __('Receiver') }}</th>
                            <th data-filter-control-placeholder="Created At"
                                data-field="Created At" data-sortable="true">
                                {{ __('Created At') }}</th>
                            <th data-filter-control-placeholder="Read At"
                                data-field="Read At" data-sortable="true">
                                {{ __('Read At') }}</th>
                            <th width="20%"></th>
                        </tr>
                    </x-slot>
                    <x-slot name="body">
                        @foreach($this->lists as $item)
                            <tr align="left" class="even:bg-white odd:bg-gray-100">
                                <td>{{$item['sender']['name']}} </td>
                                <td>{{$item['receiver']['name']}} </td>
                                <td>{{Carbon\Carbon::parse($item['created_at'])->format('d/m/Y')}}</td>
                                <td>{{!blank($item['read_at']) ? Carbon\Carbon::parse($item['read_at'])->format('d/m/Y') : ""}}</td>
                                <td x-data="{}">
                                    <div class="flex">
                                        {{--                                        <a href="javascript:void(0)" class="button-secondary message-table me-2"--}}
                                        {{--                                           x-on:click="$wire.openFormModal({{ $item->id }})"--}}
                                        {{--                                        >--}}
                                        {{--                                            Edit--}}
                                        {{--                                        </a>--}}
                                        <a href="javascript:void(0)" class="button-green message-table me-2"
                                           x-on:click="$wire.openViewModal({{ $item }})"
                                        >
                                            View
                                        </a>
                                        <a href="javascript:void(0)" class="button-secondary message-table"
                                           x-on:click="$wire.openDeleteModal({{ $item }})"
                                        >
                                            {{ __('Delete') }}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                    <x-slot name="hasMorePages">
                        {{$hasMorePages}}
                    </x-slot>
                </x-data-table.infinite-table>
        @endif


        <!-- Delete Modal -->
            <x-jet-modal wire:model="showDeleteModal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content p-4">
                        <div class="modal-body p-0 row">
                            <div class="row col-12 p-0 m-0">
                                <h5 class="black-color col fw-bold mb-4">
                                    Are you sure want to delete this message?
                                    <button type="button"
                                            class="btn-close opacity-1 float-right align-top align-self-start ms-auto col-auto me-2"
                                            wire:click.prevent="closeModal()" aria-label="Close"></button>
                                </h5>
                            </div>
                            <div class="row col-12 m-0">
                                <a class="button-secondary col"
                                   wire:click.prevent="closeModal" wire:loading.attr="disabled"
                                   aria-label="Close"
                                   href="javascrip:void(0);">Cancel</a>

                                @php
                                    $viewItem = optional($selectedItem);
                                @endphp

                                <a class="button-green ms-2 col" href="javascrip:void(0);"
                                   wire:click.prevent="confirmDelete()"
                                   wire:loading.attr="disabled">Yes</a>
                            </div>
                        </div>
                    </div>
                </div>
            </x-jet-modal>

            <!-- View Modal -->
            <x-jet-modal wire:model="showModal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content p-4">
                        <div class="modal-body p-0 row">
                            <div class="row col-12 p-0 m-0">
                                <h5 class="black-color col fw-bold mb-4">
                                    Detail
                                    <button type="button"
                                            class="btn-close opacity-1 float-right align-top align-self-start ms-auto col-auto me-2"
                                            wire:click.prevent="closeModal()" aria-label="Close"></button>
                                </h5>
                            </div>
                            <div class="my-5">

                                @php
                                    $viewItem  = optional($selectedItem);
                                @endphp

                                <div class="table table-responsive">
                                    <table class="table table-striped">
                                        <tbody>
                                            <tr>
                                                <th>Type</th>
                                                <td>{{$viewItem->type}}</td>
                                            </tr>
                                            <tr>
                                                <th>Sender</th>
                                                <td>{{optional($viewItem->sender)->name}}</td>
                                            </tr>
                                            <tr>
                                                <th>Receiver</th>
                                                <td>{{optional($viewItem->receiver)->name}}</td>
                                            </tr>
                                            <tr>
                                                <th>Message</th>
                                               <td>{!! $viewItem->data !!}</td>
                                            </tr>
                                            <tr>
                                                <th>Link</th>
                                                <td>{{$viewItem->link}}</td>
                                            </tr>
                                            <tr>
                                                <th>Read</th>
                                                <td>{{$viewItem->read_at ? \Carbon\Carbon::parse($viewItem->read_at)->format('Y/m/d H:i:s') : ""}}</td>
                                            </tr>
                                            <tr>
                                                <th>Created At</th>
                                                <td>{{\Carbon\Carbon::parse($viewItem->created_at)->format("Y/m/d")}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-jet-modal>

            <!-- Add | Edit Modal -->
            <x-jet-modal wire:model="showFormModal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content p-4">
                        <div class="modal-body p-0 row">
                            <div class="row col-12 p-0 m-0">
                                <h5 class="black-color col fw-bold mb-4">
                                    {{ $editing ? 'Edit Item' : 'Add Item' }} Form
                                    <button type="button"
                                            class="btn-close opacity-1 float-right align-top align-self-start ms-auto col-auto me-2"
                                            wire:click.prevent="closeModal()" aria-label="Close"></button>
                                </h5>
                            </div>
                            <div class="mb-5">
                                <div class="bg-white block-main row m-0  h-100">
                                    <div class="col-12 p-0">
                                        {{--                                        <div class="row mb-30 fs-16 buy-desc">--}}
                                        {{--                                            <label for="user_id"--}}
                                        {{--                                                   class="form-label p-0 black-color">{{!$editing ? __('Users')--}}
                                        {{--                                                :  __('User') }}</label>--}}
                                        {{--                                            @if(!$editing)--}}
                                        {{--                                                <div class="relative">--}}
                                        {{--                                                    <!-- Selection Input -->--}}
                                        {{--                                                    <select multiple wire:model="userSelectedId"--}}
                                        {{--                                                            class="w-full border rounded-md px-4 py-2 pr-8 focus:outline-none focus:ring focus:border-blue-300">--}}
                                        {{--                                                        @foreach ($users as  $value)--}}
                                        {{--                                                            <option--}}
                                        {{--                                                                value="{{ $value['id'] }}">{{ $value['name'] }}</option>--}}
                                        {{--                                                        @endforeach--}}
                                        {{--                                                    </select>--}}

                                        {{--                                                    <!-- Clear Selection Button -->--}}
                                        {{--                                                    @if (count($userSelectedId) > 0)--}}
                                        {{--                                                        <button wire:click="clearSelection"--}}
                                        {{--                                                                class="absolute top-2 right-2 text-gray-600 hover:text-gray-800 focus:outline-none">--}}
                                        {{--                                                            Clear Selection--}}
                                        {{--                                                        </button>--}}
                                        {{--                                                    @endif--}}
                                        {{--                                                </div>--}}
                                        {{--                                                <div class="mt-4">--}}
                                        {{--                                                    <p>Selected Items:</p>--}}
                                        {{--                                                    <ul class="list-disc pl-6">--}}
                                        {{--                                                        @foreach ($userSelectedId as $key=> $value)--}}
                                        {{--                                                            <li>{{ optional(collect($users)->where('id',$value)->first())->name }}</li>--}}
                                        {{--                                                        @endforeach--}}
                                        {{--                                                    </ul>--}}
                                        {{--                                                </div>--}}
                                        {{--                                            @else--}}
                                        {{--                                                {{optional($item->receiver)->name}}--}}
                                        {{--                                            @endif--}}
                                        {{--                                            <x-jet-input-error for="userSelectedId" class="mt-2" />--}}
                                        {{--                                        </div>--}}

                                        <div class="row mb-30 fs-16 buy-desc">
                                            <label for="message"
                                                   class="form-label p-0 black-color">{{ __('Message') }}</label>
                                            <textarea class="form-control default" rows="10" wire:model="message"
                                                      id="message"></textarea>
                                            <x-jet-input-error for="message" class="mt-2" />
                                        </div>

                                        <div class="row mb-30 mt-30">
                                            <button type="button" class="button-green w-100  button-next"
                                                    wire:click="saveItem">Save
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-jet-modal>
        </div>
    </div>
</div>
<script>
    window.addEventListener('confirm-delete', event => {
        Livewire.emit('openConfirmationModal', event.detail.itemId);
    });

    window.addEventListener('reloadPage', (e) => {
        setTimeout(function () {
            window.location.reload();
        }, 2000);
    });
</script>
