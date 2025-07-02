<div>
    <div class="row col-12 p-0 m-0 h-100">
        <div class="bg-white block-main row m-0 table-container p-0 h-100 pb-32 flex-column d-none d-sm-block">
            <div class="col-12 p-32 row m-0">
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
                    <div class="row col m-4 mt-0 mb-0 p-0">
                        <h6 class="fw-bold p-0 col-auto">My Credits</h6>
                        <a class="button-green rounded-pill ms-auto" href="javascript:void(0)"
                           wire:click="$emit('openCloseAddCertificateModal')">
                            <svg class="icon icon-plus me-2" width="16" height="16">
                                <use href="{{asset('img/icons.svg#icon-plus')}}"></use>
                            </svg>
                            Add Carbon Credit
                        </a>
                        @push('modals')
                            @livewire("certificate.add-certificate-modal",['certificate'=>$certificate])
                        @endpush
                    </div>
                    <div class="m-0 p-0">
                        <x-data-table.infinite-table :model="$lists" :columns="[]" :wantSearching="true" :dateFilter="true">
                            <x-slot name="title">
                            </x-slot>
                            <x-slot name="head">
                                <tr>
                                    <th data-filter-control-placeholder="Type" data-field="Type" data-width="20"
                                        data-width-unit="%">
                                        {{ __('Type') }}</th>
                                    <th data-filter-control-placeholder="Name" data-field="Name">
                                        {{ __('Name') }}</th>
                                    <th data-filter-control-placeholder="Country" data-field="Country">
                                        {{ __('Country') }}</th>
                                    <th data-filter-control-placeholder="Quantity" data-field="Quantity"
                                        style="width: 8%;">
                                        {{ __('Quantity') }}</th>
                                    <th data-filter-control-placeholder="Status" data-field="Status" data-width="14"
                                        data-width-unit="%" style="width: 10%;">
                                        {{ __('Status') }}</th>
                                    <th data-filter-control-placeholder="Action" data-field="Action" data-width="14"
                                        data-width-unit="%" style="width: 12%;">
                                        {{ __('Action') }}
                                    </th>
                                </tr>
                            </x-slot>
                            <x-slot name="body">
                                @foreach($this->lists as $sellCertificate)
                                    <tr>
                                        <td x-data="{}">
                                            <a href="javascript:void(0)"
                                               class="text-decoration-none text-black"
                                               x-on:click="$wire.showCertificate({{ $sellCertificate['id'] }})">
                                                <div class="d-flex ml-1 align-items-center">
                                                    <svg class="icon icon-Forest-ERB me-2" width="32" height="32">
                                                        <use
                                                            href="{{asset('img/icons.svg#'.$sellCertificate['certificate']['project_type']['image_icon'])}}"></use>
                                                    </svg>{{$sellCertificate['certificate']['project_type']['type']}}
                                                </div>
                                            </a>
                                        </td>
                                        <td>{{$sellCertificate['certificate']['name']}}</td>
                                        <td>{{$sellCertificate['certificate']['country']['name']}}</td>
                                        <td>{{$sellCertificate['remaining_units']}}</td>
                                        <td>
                                            @if($sellCertificate['status'] == 1)
                                                <div class="status-button pending">
                                                    Pending
                                                </div>
                                            @elseif($sellCertificate['status'] == 2)
                                                <div class="status-button approved">
                                                    Approved
                                                </div>
                                            @elseif($sellCertificate['status'] == 3)

                                                <div class="status-button onsell">
                                                    On Sell
                                                </div>
                                            @else
                                                <div class="status-button notapproved">
                                                    Declined
                                                </div>
                                            @endif
                                        </td>
                                        <td x-data="{}">
                                            <div class="flex justify-content-around">
                                                @if($sellCertificate['status'] ==2 )
                                                    <a href="#"
                                                       x-on:click="$wire.openSellModal({{ $sellCertificate['id'] }})"
                                                       class="button-green sell-table"
                                                       type="button">
                                                        {{ __('Sell') }}
                                                    </a>
                                                @else
                                                    <a href="#"
                                                       x-on:click="$wire.openSellModal({{ $sellCertificate['id'] }})"
                                                       class="button-green sell-table"
                                                       type="button"
                                                       disabled="">
                                                        {{ __('Sell') }}
                                                    </a>
                                                @endif
                                                <select name="sell-menu-select"
                                                        class="selectpicker-class button-grey dropdown-grey ms-1 col-auto me-2 sell-menu-select"
                                                        data-container="body"
                                                        title="See more info"
                                                        wire:model.defer="viewOrCancel">
                                                    <option value="view_{{$sellCertificate['id']}}">View</option>
                                                    @if($sellCertificate['status']== \App\Models\SellCertificate::STATUS_ON_SELL)
                                                        <option value="cancel_{{$sellCertificate['id']}}">
                                                            {{ __('Cancel') }}
                                                        </option>
                                                    @endif
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </x-slot>
                            <x-slot name="hasMorePages">
                                {{$hasMorePages}}
                            </x-slot>
                        </x-data-table.infinite-table>
                    </div>


                    <x-jet-modal class="modal fade" wire:model="confirmingUserDeletion">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content p-32">
                                <div class="modal-body p-0 row">
                                    <div class="row col-12 p-0 m-0">
                                        <h5 class="black-color col fw-bold mb-20">
                                            <svg class="icon icon-Forest-ERB me-2" width="32" height="32">
                                                <use href="{{asset('img/icons.svg#icon-Forest-ERB')}}"></use>
                                            </svg>
                                            Sell {{ $title }}
                                        </h5>
                                        <button type="button"
                                                class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2"
                                                wire:click="closeModal"></button>
                                        <div class="col-12 d-flex align-items-baseline mb-20">
                                            <h5 class="price fw-bold me-3">${{ price_format($amount)}}</h5>
                                            <span
                                                class="statistic-price statistic-increase d-flex align-items-center fs-12">
                                            <svg class="icon icon-triangle-top me-1" width="8" height="8">
                                                <use href="{{asset('img/icons.svg#icon-triangle-top')}}"></use>
                                            </svg>3.46 (+0.54)
                                        </span>
                                        </div>
                                    </div>
                                    <div class="row col-12 mx-0 black-10 mb-24">
                                        <hr class="m-0">
                                    </div>
                                    <form class="row col-12 p-0 m-0">
                                        <div class="col-12 mb-24 row d-flex align-items-center">
                                            <div class="col-3"><label for="QTY" class="fs-16">QTY</label></div>
                                            <div class="col-9 d-flex">
                                            <span class="input-group-prepend col-auto">
                                                <button type="button" class="btn button-green btn-number"
                                                        wire:click="decreaseAmount" data-type="minus"
                                                        data-field="QTY"><svg class="icon icon-Minus-Icon" width="24"
                                                                              height="24">
                                                        <use href="{{asset('img/icons.svg#icon-Minus-Icon')}}"></use>
                                                    </svg>
                                                </button>
                                            </span>
                                                <input type="text" wire:model="unit" readonly
                                                       class="form-control default mx-2 w-50 text-center fw-bold"
                                                       name="QTY" id="QTY" min="0" max="100" value="75">
                                                <span class="input-group-append col-auto">
                                                <button type="button" class="btn button-green btn-number"
                                                        data-type="plus" wire:click="increaseAmount" data-field="QTY">
                                                    <svg class="icon icon-Plus-Icon" width="24" height="24">
                                                        <use href="{{asset('img/icons.svg#icon-Plus-Icon')}}"></use>
                                                    </svg>
                                                </button>
                                            </span>
                                            </div>
                                        </div>
                                        <div class="col-12 mb-24">
                                            <hr class="m-0">
                                        </div>

                                        <div class="col-12 d-flex justify-content-between mb-20">
                                            <div
                                                class="col-auto fs-16 d-flex justify-content-center align-items-center">
                                                Total:
                                            </div>
                                            <div
                                                class="col-auto fs-16 d-flex justify-content-center align-items-center">
                                                USD <span
                                                    class="ms-2 fw-bold fs-24">{{ price_format($total)}}</span></div>
                                        </div>
                                        @if(!$bank)
                                            <div class="col-12 mb-24">
                                                <hr class="m-0">
                                            </div>
                                            <div class="col-12 d-flex justify-content-between mb-20">
                                                <div
                                                    class="col-auto fs-16 d-flex justify-content-center align-items-center">
                                                    Your Bank Details is missing
                                                </div>
                                                <div
                                                    class="col-auto fs-16 d-flex justify-content-center align-items-center">
                                                    <a class="button-green rounded-pill w-100"
                                                       wire:click="$emit('openCloseBankFormModal')"
                                                       href="javascript:void(0)">Add Bank Account</a></div>
                                            </div>
                                            <div class="row col-12 m-0">
                                                <a class="button-green rounded-pill w-100" wire:click="sellCertificate"
                                                   href="javascript:void(0)" disabled>Sell</a>
                                            </div>
                                        @else
                                            <div class="row col-12 m-0">
                                                <a class="button-green rounded-pill w-100" wire:click="sellCertificate"
                                                   href="javascript:void(0)">Sell</a>
                                            </div>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                    </x-jet-modal>

                @push('modals')
                    @livewire('certificate.sell-certificate-modal')
                    @livewire('certificate.cancel-sell-certificate-modal')
                    @livewire("profile.payment.bank-form-modal",['bank'=>$bank])
                @endpush

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
            <div class="d-flex">
                <h4 class="fw-bold p-0 mb-4">My Credits</h4>
                <a class="button-green rounded-pill ms-auto" href="javascript:void(0)"
                   wire:click="$emit('openCloseAddCertificateModal')">
                    <svg class="icon icon-plus me-2" width="16" height="16">
                        <use href="{{asset('img/icons.svg#icon-plus')}}"></use>
                    </svg>
                    Add Carbon Credit
                </a>
            </div>
            @forelse ($lists as $sellCertificate)
                <div class="card-el p-3 mb-3">
                    <div class="card-header d-flex justify-content-between">
                        <a  class="text-decoration-none text-black" wire:click="showCertificate({{ $sellCertificate['id'] }})">
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
                        <div class="flex justify-content-around">
                            @if($sellCertificate['status'] ==2 )
                                <a href="#"
                                   x-on:click="$wire.openSellModal({{ $sellCertificate['id'] }})"
                                   class="button-green sell-table"
                                   type="button">
                                    {{ __('Sell') }}
                                </a>
                            @else
                                <a href="#"
                                   x-on:click="$wire.openSellModal({{ $sellCertificate['id'] }})"
                                   class="button-green sell-table"
                                   type="button"
                                   disabled="">
                                    {{ __('Sell') }}
                                </a>
                            @endif
                            <select name="sell-menu-select"
                                    class="selectpicker-class button-grey dropdown-grey ms-1 col-auto me-2 sell-menu-select"
                                    data-container="body"
                                    title="See more info"
                                    wire:model.defer="viewOrCancel">
                                <option value="view_{{$sellCertificate['id']}}">View</option>
                                @if($sellCertificate['status']== \App\Models\SellCertificate::STATUS_ON_SELL)
                                    <option value="cancel_{{$sellCertificate['id']}}">
                                        {{ __('Cancel') }}
                                    </option>
                                @endif
                            </select>
                        </div>
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

<div class="modal fade" id="phoneModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4">
            <div class="modal-body p-0 row">
                <div class="row col-12 p-0 m-0">
                    <h4 class="black-color col fw-bold mb-2">
                        Validate Your Phone Number
                    </h4>
                    <button type="button"
                            class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="row col-12 p-0 m-0 mb-3">
                    <p class="text-left text-secondary fs-16 lh-base">REWIND activities require you to validate your
                        phone
                        number
                        via the SMS message you receive during the registration
                        process. Please press the "resend" button if you cannot find it.</p>
                </div>
                <div class="row col-12 m-0">
                    <a class="button-green w-100 button-send" href="#">Re-Send Validation SMS
                        Message</a>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    let myCharts = [];
    // let count = 0;
    function refreshGraph(){
        if(myCharts.length){
            for (let i = 0; i < myCharts.length; i++) {
                myCharts[i].destroy();
            }
        }
        if ($('canvas.small-graph-sell').length) {
            $('canvas.small-graph-sell').each(function (index,el) {
                let data = $(el).attr('data-chart');
                let datas = JSON.parse(data);
                let maxValue = $(this).data('max-val');
                var ctx = this.getContext('2d');
                var gradient = ctx.createLinearGradient(0, 0, 0, 40);
                gradient.addColorStop(0, 'rgba(205,242,205,1)');
                gradient.addColorStop(1, 'rgba(205,242,205,0)');
                let mainSettings = {
                    type: 'line',
                    data: {
                        labels: datas.map(a => a['date']),
                        datasets: [
                            {
                                fill: true,
                                backgroundColor: gradient,
                                borderColor: '#55D168',
                                borderWidth: 1,
                                pointRadius: 0,
                                hitRadius: 0,
                                data: datas,
                            },
                        ],
                    },
                    interaction: {
                        intersect: false,
                    },
                    options: {
                        plugins: {
                            legend: {
                                display: false,
                            },
                            parsing: {
                                xAxisKey: 'x',
                                yAxisKey: 'y',
                            },
                        },
                        responsive: true,
                        tension: 0.5,
                        scales: {
                            y: {
                                min: 0,
                                max: maxValue,
                                scales: {
                                    type: 'linear',
                                },
                                ticks: {
                                    display: false,
                                },
                                grid: {
                                    display: false,
                                    borderWidth: 0,
                                },
                            },
                            x: {
                                scales: {
                                    type: 'linear',
                                },
                                grid: {
                                    display: false,
                                    borderWidth: 0,
                                },
                                ticks: {
                                    display: false,
                                },
                            },
                        },
                    },
                };
                let chart = new Chart(this, mainSettings);
                myCharts.push(chart);
            });
        }
    }
    document.addEventListener("livewire:load", function (event) {
        refreshGraph();
        $(document)
        .on('changed.bs.select','.selectpicker-class', function (e) {
            Livewire.emit('callViewOrCancel');
        });
    });
    Livewire.hook('message.processed', () => {
        refreshGraph();
    });
</script>

@endpush
{{--old code--}}
</div>



