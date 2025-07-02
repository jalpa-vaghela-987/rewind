<div>
    <div class="col row p-3 table-container d-none d-sm-block">
        <div class="row col-12">
            <p class="fw-bold fs-16">Transactions History</p>
        </div>
        <div class="col-12 p-3 row m-0">
            <div class="search-wrap">
                <input wire:model="search" type="search" class="base-search w-100" id="table_search" placeholder="Search">
                <span class="icon">
                <svg class="icon icon-search" width="20" height="20">
                    <use href="{{asset('img/icons.svg#icon-search')}}"></use>
                </svg>
            </span>
            </div>
        </div>
        <div class="row col-12  mb-24">
            <x-data-table.infinite-table :model="$lists" :columns="[]" :wantSearching="true" :dateFilter="true">
                <x-slot name="title">
                </x-slot>
                <x-slot name="head">
                    <tr>
                        <th>
                            {{ __('Type') }}</th>
                        <th>
                            {{ __('Name') }}</th>
                        <th>
                            {{ __('Date') }}</th>
                        <th>
                            {{ __('Quantity') }}</th>
                        <th>
                            {{ __('Amount') }}</th>
                        <th>{{ __('Transaction Type') }}</th>
                    </tr>
                </x-slot>
                <x-slot name="body">
                    @foreach($this->lists as $transaction)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <svg class="icon {{$transaction['certificate']['project_type']['image']}} me-2 float-left" width="32" height="32">
                                        <use href="{{asset('/img/icons.svg#'.$transaction['certificate']['project_type']['image'])}}"></use>
                                    </svg>
                                    {{$transaction['certificate']['project_type']['type']}}
                                </div>
                            </td>
                            <td>{{$transaction['certificate']['name']}}</td>
                            <td>{{date('d/M/Y',strtotime($transaction['created_at']))}}</td>
                            <td>{{$transaction['amount']}}</td>
                            <td>{{ price_format($transaction['amount'])}}$</td>
                            <td><a
                                    wire:click.prevent="$emit('openTransactionDetailModal','{{base64_encode($transaction['id'])}}')"
                                    href="javascript:void(0);"
                                    type="button" class="label btn sale">{{ ($transaction['user_id'] == auth()->user()->id)?__('Buy'):__('Sale') }}</a>
                            </td>
                        </tr>
                    @endforeach
                </x-slot>
                <x-slot name="hasMorePages">
                    {{$hasMorePages}}
                </x-slot>
            </x-data-table.infinite-table>
        </div>
    </div>
    <div class="index-list-sm d-block d-sm-none p-0 mt-5">
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
        <div class="d-flex">
            <h4 class="fw-bold p-0 mb-4">Transactions History</h4>
        </div>
        @forelse($this->lists as $transaction)
            <div class="card-el p-3 mb-3">
                <div class="card-header d-flex justify-content-between">
                    <a href="#" class="text-decoration-none text-black">
                        <div class="d-flex align-items-center">
                            <svg class="icon {{$transaction['certificate']['project_type']['image']}} me-2" width="32" height="32">
                                <use href="../img/icons.svg#{{$transaction['certificate']['project_type']['image']}}">
                                </use>
                            </svg>
                            <div class="d-flex flex-column">
                                <div class="title fw-bold">{{$transaction['certificate']['name']}}</div>
                                <div class="title">{{$transaction['certificate']['project_type']['type']}}</div>
                            </div>
                        </div>
                    </a>
                    <span class="d-flex flex-column align-items-end justify-content-space-between">
                    <span class="price fw-bold">  ${{ price_format($transaction['amount'])}}</span>
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
                        <span class="result fw-bolder"> {{$transaction['certificate']['country']['name']}}</span>
                    </p>
                    <p class="d-flex justify-content-between align-items-center mb-2">
                        <span class="title fw-bold text-black-50 fw-bolder">Date:</span>
                        <span class="result fw-bolder"> {{date('d/M/Y',strtotime($transaction['created_at']))}}</span>
                    </p>

                </div>
                <hr class="opacity-25">
                <div class="d-flex justify-content-end">
                    <a
                        wire:click.prevent="$emit('openTransactionDetailModal','{{base64_encode($transaction['id'])}}')"
                        href="javascript:void(0);"
                        type="button" class="label btn sale">{{ ($transaction['user_id'] == auth()->user()->id)?__('Buy'):__('Sale') }}</a>
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
    @push('modals')
        @livewire('profile.transaction.transaction-detail-modal')
    @endpush
</div>
