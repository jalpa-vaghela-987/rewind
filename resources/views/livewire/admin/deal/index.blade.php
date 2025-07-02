<div class="row col-12 p-0 m-0 h-100">
    <div class="bg-white block-main row m-0 table-container p-0 h-100 pb-32 flex-column d-none d-sm-block">
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
                <div class="row col mb-2 m-4 p-0 mt-0">
                    <h6 class="fw-bold p-0 col-auto">Deals</h6>
                </div>

                <x-data-table.infinite-table :model="$lists" :columns="[]" :wantSearching="true" :dateFilter="true">
                    <x-slot name="title">
                    </x-slot>
                    <x-slot name="head">
                        <tr>
                            <th data-filter-control-placeholder="Type"
                                data-field="Type" data-sortable="true" width="20%">
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
                            <th data-filter-control-placeholder="Value" data-field="Value"
                                data-sortable="true">
                                {{ __('Value') }}</th>
                            <th data-filter-control-placeholder="Buyer" data-field="Buyer"
                                data-sortable="true">
                                {{ __('Buyer') }}</th>
                            <th data-filter-control-placeholder="Seller" data-field="Seller"
                                data-sortable="true">
                                {{ __('Seller') }}</th>
                            <th data-filter-control-placeholder="Date" data-field="Date"
                                data-sortable="true">
                                {{ __('Date') }}</th>
                        </tr>
                    </x-slot>
                    <x-slot name="body">
                        @foreach($this->lists as $deal)
                            <tr align="left" class="even:bg-white odd:bg-gray-50">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <svg width="32" height="32" class="icon icon-Forest-ERB mr-2">
                                            <use
                                                href="{{asset('img/icons.svg#'.$deal['certificate']['project_type']['image_icon'])}}"></use>
                                        </svg> {{ $deal['certificate']['project_type']['type'] }}
                                    </div>
                                </td>
                                <td>{{ $deal['certificate']['name'] }}</td>
                                <td>{{ $deal['certificate']['country']['name'] }}</td>
                                <td>{{ $deal['quantity'] }}</td>
                                <td><span class="price fw-bold">${{ price_format($deal['amount']) }}</span></td>
                                <td>{{ $deal['buyer']['name'] }}</td>
                                <td>{{ $deal['seller']['name'] }}</td>
                                <td>{{Carbon\Carbon::parse($deal['created_at'])->format('d/m/Y')}}</td>
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
        <h4 class="fw-bold p-0 mb-4">Carbon Credits</h4>
        @forelse ($lists as $deal)
            <div class="card-el p-3 mb-3">
                <div class="card-header d-flex justify-content-between">
                    <a  class="text-decoration-none text-black">
                        <div class="d-flex align-items-center">
                            <svg class="icon {{$deal['certificate']['project_type']['image']}} me-2" width="32"
                                 height="32">
                                <use href="../img/icons.svg#{{$deal['certificate']['project_type']['image']}}">
                                </use>
                            </svg>
                            <div class="d-flex flex-column">
                                <div class="title fw-bold">{{$deal['certificate']['name']}}</div>
                                <div class="title">{{$deal['certificate']['project_type']['type']}}</div>
                            </div>
                        </div>
                    </a>
                    <span class="d-flex flex-column align-items-end justify-content-space-between">
                    <span class="price fw-bold">  ${{ price_format($deal['amount'])}}</span>
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
                        <span class="result fw-bolder"> {{$deal['certificate']['country']['name']}}</span>
                    </p>
                    <p class="d-flex justify-content-between align-items-center mb-2">
                        <span class="title fw-bold text-black-50 fw-bolder">Quantity:</span>
                        <span class="result fw-bolder"> {{ $deal['quantity']}}</span>
                    </p>
                    <p class="d-flex justify-content-between align-items-center mb-2">
                        <span class="title fw-bold text-black-50 fw-bolder">Buyer:</span>
                        <span class="result fw-bolder"> {{ $deal['buyer']['name'] }}</span>
                    </p>
                    <p class="d-flex justify-content-between align-items-center mb-2">
                        <span class="title fw-bold text-black-50 fw-bolder">Seller:</span>
                        <span class="result fw-bolder"> {{ $deal['seller']['name'] }}</span>
                    </p>
                    <p class="d-flex justify-content-between align-items-center mb-2">
                        <span class="title fw-bold text-black-50 fw-bolder">Date:</span>
                        <span class="result fw-bolder"> {{ Carbon\Carbon::parse($deal['created_at'])->format('d/m/Y') }}</span>
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
