<div>
    <div class="row col-12 p-0 m-0 h-100">
        <div class="bg-white block-main row m-0 table-container h-100 p-3 flex-column d-none d-sm-block">
            <div class="p-0 row m-0">
                <div class="row col-12">
                    <h6 class="fw-bold p-0 col-auto">My Carbon Credits</h6>
                </div>
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
            </div>
            <div class="col-12 p-0 row m-0">
                <x-data-table.infinite-table :model="$lists" :columns="[]" :wantSearching="true" :dateFilter="true">
                    <x-slot name="head">
                        <tr>
                            @foreach ($headers as $key => $heading)
                                @if($key=='remove_certificate')
                                    <th style="width: 200px">{{ __($heading) }}</th>
                                @else
                                    <th data-filter-control-placeholder="{{$heading}}" data-field="{{$heading}}"
                                        data-width="20"
                                        data-width-unit="%" @if($heading == 'Type') style="width: 200px" @endif>
                                        {{ __($heading) }}</th>
                                @endif
                            @endforeach
                        </tr>
                    </x-slot>
                    <x-slot name="body">
                        @forelse ($lists as $sellCertificate)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                    <svg
                                        class="icon {{$sellCertificate['certificate']['project_type']['image']}} me-2" width="32" height="32">
                                        <use href="{{asset('/img/icons.svg#'.$sellCertificate['certificate']['project_type']['image_icon'])}}"></use>
                                    </svg>
                                    {{$sellCertificate['certificate']['project_type']['type']}}
                                    </div>
                                </td>
                                <td>
                                    {{$sellCertificate['certificate']['name']}}
                                </td>
                                <td>
                                    {{ $sellCertificate['remaining_units']}}
                                </td>
                                <td>
                                    {{ $sellCertificate['certificate']['country']['name']}}
                                </td>
                                <td>
                                    ${{ price_format($sellCertificate['price_per_unit'])}}
                                </td>
                                <td>
                                    @if($sellCertificate['status'] == 1)
                                        <div class="status-button pending  fw-normal">
                                            Pending
                                        </div>
                                    @elseif($sellCertificate['status'] == 2)
                                        <div class="status-button approved  fw-normal">
                                            Approved
                                        </div>
                                    @elseif($sellCertificate['status'] == 3)
                                        <div class="status-button onsell  fw-normal">
                                            On Sell
                                        </div>
                                    @else
                                        <div class="status-button notapproved  fw-normal">
                                            Declined
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($sellCertificate['is_main'] && $sellCertificate['units'] == $sellCertificate['remaining_units'])
                                        <a href="javascript:void(0)" class="button-secondary"
                                           wire:click="openDeleteCertificateModal({{$sellCertificate['id']}})">{{ __('Remove') }}
                                        </a>
                                    @else
                                        <a href="javascript:void(0)" class="button-secondary" disabled>
                                            {{ __('Remove') }}
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">
                                    <p>No matching records found</p>
                                </td>
                            </tr>
                        @endforelse
                    </x-slot>
                    <x-slot name="hasMorePages">
                        {{$hasMorePages}}
                    </x-slot>
                </x-data-table.infinite-table>
            </div>
        </div>
        <div class="index-list-sm d-block d-sm-none p-0">
            <h4 class="fw-bold p-0 mb-4">My Carbon Credits</h4>
            @forelse ($lists as $sellCertificate)
                <div class="card-el p-3 mb-3">
                    <div class="card-header d-flex justify-content-between">
                        <a class="text-decoration-none text-black">
                            <div class="d-flex align-items-center">
                                <svg class="icon {{$sellCertificate['certificate']['project_type']['image']}} me-2" width="32" height="32">
                                    <use href="./img/icons.svg#{{$sellCertificate['certificate']['project_type']['image']}}">
                                    </use>
                                </svg>
                                <div class="d-flex flex-column">
                                    <div class="title fw-bold">{{$sellCertificate['certificate']['name']}}</div>
                                    <div class="title">{{$sellCertificate['certificate']['project_type']['type']}}</div>
                                </div>
                            </div>
                        </a>
                        <span class="d-flex flex-column align-items-end justify-content-space-between">
                    <span class="price fw-bold">  ${{ price_format($sellCertificate['price_per_unit'])}}</span>
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
                            <span class="result fw-bolder"> {{$sellCertificate['certificate']['country']['name']}}</span>
                        </p>
                        <p class="d-flex justify-content-between align-items-center mb-2">
                            <span class="title fw-bold text-black-50 fw-bolder">Quantity:</span>
                            <span class="result fw-bolder"> {{ $sellCertificate['remaining_units']}}</span>
                        </p>
                        <p class="d-flex justify-content-between align-items-center">
                            <span class="title fw-bold text-black-50 fw-bolder">Status:</span>

                        @if($sellCertificate['status'] == 1)
                            <span class="status-button pending  fw-normal">
                                Pending
                            </span>
                        @elseif($sellCertificate['status'] == 2)
                            <span class="status-button approved  fw-normal">
                                Approved
                            </span>
                        @elseif($sellCertificate['status'] == 3)
                            <span class="status-button onsell  fw-normal">
                                On Sell
                            </span>
                        @else
                            <span class="status-button notapproved  fw-normal">
                                Declined
                            </span>
                            @endif
                        </p>
                    </div>
                    <hr class="opacity-25">
                    <div class="d-flex justify-content-end">
                        @if($sellCertificate['is_main'] && $sellCertificate['units'] == $sellCertificate['remaining_units'])
                            <a href="javascript:void(0)" class="button-secondary"
                               wire:click="openDeleteCertificateModal({{$sellCertificate['id']}})">{{ __('Remove') }}
                            </a>
                        @else
                            <a href="javascript:void(0)" class="button-secondary" disabled>
                                {{ __('Remove') }}
                            </a>
                        @endif
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
            @livewire('portfolio.delete-certificate-modal')
        @endpush
    </div>

</div>
