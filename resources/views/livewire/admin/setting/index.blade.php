<div class="row col-12 p-0 m-0 h-100">
    <div class="bg-white block-main row m-0 table-container p-0 h-100 pb-32 flex-column">
        <div class="col-12 p-3 row m-0">
            <div class="search-wrap">
                <input type="search" class="base-search w-100" id="table_search" placeholder="Search"
                       wire:model="search">
                <span class="icon">
                <svg class="icon icon-search" width="20" height="20">
                    <use href="{{asset('img/icons.svg#icon-search')}}"></use>
                </svg>
            </span>
            </div>
        </div>
        <div class="col-12 p-0 row m-0">
            @if(!empty($lists))
                <div class="row col mb-24 m-3 p-0 mt-0">
                    <h6 class="fw-bold p-0 col-auto">Roles</h6>
                    <a class="button-green ms-auto" href="javascript:void(0)" wire:click="$emit('openRoleModal')">
                        <svg class="icon icon-plus me-2" width="16" height="16">
                            <use href="{{asset('img/icons.svg#icon-plus')}}"></use>
                        </svg>
                        Add Roles</a>

                </div>
                <x-data-table.infinite-table :model="$lists" :columns="[]" :wantSearching="true" :dateFilter="true">
                    <x-slot name="head">
                        <tr>
                            <th data-filter-control-placeholder="Id" data-field="Id"
                                data-sortable="false">
                                {{ __('id') }}</th>
                            <th data-filter-control-placeholder="Name" data-field="Name"
                                data-sortable="true">
                                {{ __('Name') }}</th>

                            <th data-filter-control-placeholder="Action" data-field="Action"
                                data-sortable="false">
                                {{ __('Action') }}</th>
                        </tr>
                    </x-slot>
                    <x-slot name="body">
                        @forelse ($lists as $key => $role)
                            <tr align="left" class="even:bg-white odd:bg-gray-50">

                                <td>{{$key+1}}</td>
                                <td>{{ $role['name'] }}</td>
                                <td>
                                    <a href="javascript:void(0)" class="button-secondary"
                                       wire:click="openRoleModal({{$role['id']}})">{{ __('Edit') }}
                                    </a>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">
                                    <p>No matching records found</p>
                                </td>
                            </tr>
                        @endforelse
                    </x-slot>
                    <x-slot name="hasMorePages">
                        {{$hasMorePages}}
                    </x-slot>
                </x-data-table.infinite-table>
            @endif
                @push('modals')
                    @livewire("admin.setting.add-role")
                @endpush
        </div>
    </div>
</div>
