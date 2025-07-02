<div class="row col-12 p-0 m-0 h-100">
    <div class="bg-white block-main row m-0 table-container p-0 h-100 pb-32 flex-column d-none d-sm-block">
        <div class="col-12 p-3 row m-0">
            <div class="search-wrap">
                <input wire:model="search" type="search" class="base-search w-100" id="table_search"
                       placeholder="Search">
                <span class="icon">
                <svg class="icon icon-search" width="20" height="20">
                    <use href="{{asset('img/icons.svg#icon-search')}}"></use>
                </svg>
            </span>
            </div>
        </div>
        <div class="col-12 p-0 row m-0">
            @if(!empty($lists))
                <div class="row col mb-2 m-4 p-0 mt-0">
                    <h6 class="fw-bold p-0 col-auto">Bids</h6>
                </div>
                <x-data-table.infinite-table :model="$lists" :columns="[]" :wantSearching="true" :dateFilter="true">
                    <x-slot name="title">
                    </x-slot>
                    <x-slot name="head">
                        <tr>
                            <th data-filter-control-placeholder="Type"
                                data-field="Type" data-sortable="true">
                                {{ __('Type') }}</th>
                            <th data-filter-control-placeholder="Name" data-field="Name"
                                data-sortable="true">
                                {{ __('Name') }}</th>
                            <th data-filter-control-placeholder="Country" data-field="Country"
                                data-sortable="true">
                                {{ __('Country') }}</th>
                            <th data-filter-control-placeholder="Quantity" data-field="Quantity"
                                data-sortable="true">
                                {{ __('Quantity') }}</th>
                            <th data-filter-control-placeholder="Bid Value" data-field="bid_value"
                                data-sortable="true">
                                {{ __('Bid Value') }}</th>
                            <th data-filter-control-placeholder="Buyer" data-field="Buyer"
                                data-sortable="true">
                                {{ __('Buyer') }}</th>
                            <th data-filter-control-placeholder="Status" data-field="Status"
                                data-sortable="true">
                                {{ __('Status') }}</th>
                        </tr>
                    </x-slot>
                    <x-slot name="body">
                        @foreach($this->lists as $bid)
                            <tr align="left" class="even:bg-white odd:bg-gray-50">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <svg width="32" height="32" class="icon icon-Forest-ERB mr-2">
                                            <use
                                                href="{{asset('img/icons.svg#'.$bid['certificate']['project_type']['image_icon'])}}"></use>
                                        </svg> {{ $bid['certificate']['project_type']['type'] }}
                                    </div>
                                </td>
                                <td>{{ $bid['certificate']['name'] }}</td>
                                <td>{{ $bid['certificate']['country']['name'] }}</td>
                                <td>{{ $bid['unit'] }}</td>
                                <td>
                                    <span class="price fw-bold">${{ price_format($bid['amount']) }}</span>
                                </td>
                                <td>{{ $bid['user']['name'] }}</td>
                                <td>
                                    @if($bid['status'] == 0)
                                        <div class="status-button pending">Pending</div>
                                    @elseif($bid['status'] == 1)
                                        <div class="status-button approved">Accepted</div>
                                    @elseif($bid['status'] == 2)
                                        <div class="status-button notapproved">Decline</div>
                                    @elseif($bid['status'] == 3)
                                        <div class="status-button offered">Offered</div>
                                    @else
                                        <div class="status-button canceled">Canceled</div>
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
        <h4 class="fw-bold p-0 mb-4">Bids</h4>
        @forelse ($lists as $bid)
            <div class="card-el p-3 mb-3">
                <div class="card-header d-flex justify-content-between">
                    <a  class="text-decoration-none text-black">
                        <div class="d-flex align-items-center">
                            <svg class="icon {{$bid['certificate']['project_type']['image']}} me-2" width="32"
                                 height="32">
                                <use href="../img/icons.svg#{{$bid['certificate']['project_type']['image']}}">
                                </use>
                            </svg>
                            <div class="d-flex flex-column">
                                <div class="title fw-bold">{{$bid['certificate']['name']}}</div>
                                <div class="title">{{$bid['certificate']['project_type']['type']}}</div>
                            </div>
                        </div>
                    </a>
                    <span class="d-flex flex-column align-items-end justify-content-space-between">
                    <span class="price fw-bold">  ${{ price_format($bid['amount'])}}</span>
{{--                    <span class="statistic-price statistic-decrease d-flex  ms-1">--}}
                        {{--                        <svg class="icon icon-decrease me-1" width="16" height="16">--}}
                        {{--                            <use href="./img/icons.svg#icon-decrease"></use>--}}
                        {{--                        </svg> -0.38%</span>--}}
                </span>
                </div>
                <hr class="opacity-25">
                <div class="card-body">
                    <p class="d-flex justify-content-between align-items-center mb-2">
                        <span class="title fw-bold text-black-50 fw-bolder">Country:</span>
                        <span class="result fw-bolder"> {{$bid['certificate']['country']['name']}}</span>
                    </p>
                    <p class="d-flex justify-content-between align-items-center mb-2">
                        <span class="title fw-bold text-black-50 fw-bolder">Quantity:</span>
                        <span class="result fw-bolder"> {{ $bid['unit']}}</span>
                    </p>
                    <p class="d-flex justify-content-between align-items-center">
                        <span class="title fw-bold text-black-50 fw-bolder">Status:</span>

                    @if($bid['status'] == 0)
                        <span class="status-button pending">Pending</span>
                    @elseif($bid['status'] == 1)
                        <span class="status-button approved">Accepted</span>
                    @elseif($bid['status'] == 2)
                        <span class="status-button notapproved">Decline</span>
                    @elseif($bid['status'] == 3)
                        <span class="status-button offered">Offered</span>
                    @else
                        <span class="status-button canceled">Canceled</span>
                        @endif
                    </p>

                </div>

            </div>
        @empty
        @endforelse
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
</div>
