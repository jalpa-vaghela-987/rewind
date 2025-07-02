<div class="row col-12 p-0 m-0 h-100">
    <div class="bg-white block-main row m-0 table-container p-0 h-100 pb-32 flex-column d-none d-sm-block">
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
                    <h6 class="fw-bold p-0 col-auto">Users</h6>
                </div>
                <x-data-table.infinite-table :model="$lists" :columns="[]" :wantSearching="true" :dateFilter="true">
                    <x-slot name="title">
                    </x-slot>
                    <x-slot name="head">
                        <tr>
                            <th data-filter-control-placeholder="Name"
                                data-field="Name" data-sortable="true">
                                {{ __('Name') }}</th>
                            <th data-filter-control-placeholder="Address"
                                data-field="Address" data-sortable="true">
                                {{ __('Address') }}</th>
                            <th data-filter-control-placeholder="Email"
                                data-field="Email" data-sortable="true"
                                width="20%">
                                {{ __('Email') }}</th>
                            <th data-filter-control-placeholder="Phone number"
                                data-field="phone_number" data-sortable="true"
                                width="15%">
                                {{ __('Phone number') }}</th>
                            <th data-filter-control-placeholder="User status"
                                data-field="user_status" data-sortable="true">
                                {{ __('User status') }}</th>
                            <th data-filter-control-placeholder="Company status"
                                data-field="company_status" data-sortable="true">
                                {{ __('Company status') }}</th>
                            <th data-filter-control-placeholder="Premium validation"
                                data-field="premium_validation" data-sortable="true">
                                {{ __('Premium validation') }}</th>
                        </tr>
                    </x-slot>
                    <x-slot name="body">
                        @foreach($this->lists as $user)
                            <tr>
                                <td>{{$user['name']}} </td>
                                <td>{{$user['street']}},
                                    {{$user['city']}}
                                    {{(!empty($user['country']['name'])? ', '.$user['country']['name'] : '') }}
                                </td>
                                <td>{{$user['email']}}</td>
                                <td>{{$user['phone']}}</td>
                                <td>
                                    @if($user['status'] == 0)
                                        <div class="status-button pending cursor-pointer">
                                            <div
                                                wire:click.prevent="$emit('openApprovalModal', {{ $user }}, 'user_details')">
                                                Pending
                                            </div>
                                        </div>
                                    @elseif($user['status'] == 1)
                                        <div class="status-button approved">
                                            Active
                                        </div>
                                    @else
                                        <div class="status-button notapproved">
                                            Declined
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if(!$user['company'])
                                        not applicable
                                    @elseif($user['company'] && $user['company']['status'] == 0)
                                        <div class="status-button pending cursor-pointer">
                                            <div
                                                wire:click.prevent="$emit('openApprovalModal', {{ $user }}, 'company_details')">
                                                Pending
                                            </div>
                                        </div>
                                    @elseif($user['company'] && $user['company']['status'] == 1)
                                        <div class="status-button approved">
                                            Active
                                        </div>
                                    @else
                                        <div class="status-button notapproved">
                                            Declined
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($user['premium_validation_status'] == 0)
                                        <div class="status-button pending cursor-pointer">
                                            <div
                                                wire:click.prevent="$emit('openApprovalModal', {{ $user }}, 'premium_validation')">
                                                Pending
                                            </div>
                                        </div>
                                    @elseif($user['premium_validation_status'] == 1)
                                        <div class="status-button approved">
                                            Active
                                        </div>
                                    @else
                                        <div class="status-button notapproved">
                                            Declined
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                    <x-slot name="hasMorePages">
                        {{$hasMorePages}}
                    </x-slot>
                </x-data-table.infinite-table>
            @endif
        </div>

    </div>
    <div class="index-list-sm d-block d-sm-none p-0">
        <div class="mb-2">
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
        <h4 class="fw-bold p-0 mb-4">Users</h4>
            @foreach($this->lists as $user)
            <div class="card-el p-3 mb-3">
                <div class="card-header d-flex justify-content-between gap-1">
                    <a  class="text-decoration-none text-black w-50">
                        <div class="d-flex flex-column w-100 align-self-strech justify-content-between">
                            <div class="title fw-bold">{{$user['name']}}</div>
                            <div class="title">{{$user['street']}},
                                {{$user['city']}}
                                {{(!empty($user['country']['name'])? ', '.$user['country']['name'] : '') }}</div>
                        </div>
                    </a>
                    <span class="d-flex flex-column align-items-start justify-content-center">
                     @if($user['status'] == 0)
                            <span class="status-button pending cursor-pointer" wire:click.prevent="$emit('openApprovalModal', {{ $user }}, 'user_details')">
                                 Pending
                        </span>
                        @elseif($user['status'] == 1)
                            <span class="status-button approved">
                        Active
                    </span>
                        @else
                            <span class="status-button notapproved">
                        Declined
                    </span>
                        @endif
                </span>
                </div>
                <hr class="opacity-25">
                <div class="card-body">
                    <p class="d-flex justify-content-between align-items-center mb-2">
                        <span class="title fw-bold text-black-50 fw-bolder">Email:</span>
                        <span class="result fw-bolder w-75 text-end">{{$user['email']}}</span>
                    </p>
                    <p class="d-flex justify-content-between align-items-center">
                        <span class="title fw-bold text-black-50 fw-bolder">Phone:</span>
                        <span class="result fw-bolder w-75  text-end">{{$user['phone']}}</span>
                    </p>
                </div>
                <hr class="opacity-25">

                    <p class="d-flex justify-content-between align-items-center mb-2">
                    <span class="title fw-bold text-black-50 fw-bolder">Company status:</span>
                        @if(!$user['company'])
                            not applicable
                    @elseif($user['company'] && $user['company']['status'] == 0)
                        <span class="status-button pending cursor-pointer"   wire:click.prevent="$emit('openApprovalModal', {{ $user }}, 'company_details')">
                                Pending
                        </span>
                    @elseif($user['company'] && $user['company']['status'] == 1)
                        <span class="status-button approved">
                            Active
                        </span>
                    @else
                        <span class="status-button notapproved">
                            Declined
                        </span>
                        @endif
                    </p>
                <p class="d-flex justify-content-between align-items-center">
                    <span class="title fw-bold text-black-50 fw-bolder">Premium validation:</span>
                    @if($user['premium_validation_status'] == 0)
                        <span class="status-button pending cursor-pointer" wire:click.prevent="$emit('openApprovalModal', {{ $user }}, 'premium_validation')">
                                Pending
                        </span>
                    @elseif($user['premium_validation_status'] == 1)
                        <span class="status-button approved">
                            Active
                        </span>
                    @else
                        <span class="status-button notapproved">
                            Declined
                        </span>
                        @endif
                </p>
            </div>
            @endforeach
            @if($hasMorePages)
                <div
                    x-data="{
                                                   init () {
                                                         let observer = new IntersectionObserver((entries) => {
                                                                        entries.forEach(entry => {
                                                                        if (entry.isIntersecting) {
                                                                        @this.call('loadData')
                                                                        }
                                                                        })
                                                        }, {
                                                            root: null
                                                   });
                                                   observer.observe(this.$el);
                                                    }
                                        }"
                >

                </div>
        @endif
    </div>
    @livewire('admin.user.user-preview-and-approve-modal')
</div>
