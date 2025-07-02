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

        <div class="m-0 p-0">
            <x-data-table.infinite-table :model="$lists" :columns="[]" :wantSearching="true" :dateFilter="true">
                <x-slot name="title">
                    {{ __('Trending') }}
                </x-slot>
                <x-slot name="head">
                    <tr>
                        <th data-filter-control-placeholder="Type" data-field="Type" class="w-25"
                            data-width-unit="%">
                            {{ __('Type') }}
                        </th>
                        <th data-filter-control-placeholder="Name" data-field="Name" class="w-20">
                            {{ __('Name') }}
                        </th>
                        <th data-filter-control-placeholder="Country" data-field="Country" class="w-20">
                            {{ __('Country') }}
                        </th>
                        <th data-filter-control-placeholder="Quantity" data-field="Quantity" class="w-10">
                            {{ __('Quantity') }}
                        </th>
                        <!-- <th x-data="{}" data-filter-control-placeholder="Current value" data-field="Currentlue" width="20%">
                            <select class="selectpicker-class default" data-container="body"
                                    x-model="$wire.currentValue" wire:model="maxDays">
                                <option value="1D" >{{ __('Current value 1D') }}</option>
                                <option value="7D" >{{ __('Current value 7D') }}</option>
                                <option value="1M" >{{ __('Current value 1M') }}</option>
                                <option value="6M" >{{ __('Current value 6M') }}</option>
                            </select>
                        </th> -->
                        <th data-filter-control-placeholder="Ask Price" data-field="price_per_unit" class="w-10">
                            {{ __('Ask Price') }}
                        </th>
                        <th data-filter-control-placeholder="Buttons" data-field="Buttons"
                            class="w-25">
                        </th>
                    </tr>
                </x-slot>
                <x-slot name="body">
                    @foreach($this->lists as $sellCertificate)
                        <tr>
                            <td x-data="{}">
                                <a href="{{route('buy.show.certificate',['id' => $sellCertificate['id']])}}"
                                   class="text-decoration-none text-black">
                                    <div class="d-flex align-items-center">
                                        <svg class="icon icon-Forest-ERB me-2" width="32" height="32">
                                            <use
                                                href="{{asset('img/icons.svg#'.$sellCertificate['certificate']['project_type']['image_icon'])}}"></use>
                                        </svg>
                                        {{ $sellCertificate['certificate']['project_type']['type']??'' }}
                                    </div>
                                </a>
                            </td>
                            <td>{{$sellCertificate['certificate']['name']}}</td>
                            <td>{{$sellCertificate['certificate']['country']['name']}}</td>
                            <td>{{$sellCertificate['remaining_units']}}</td>
                            <td>
                                <div class="d-flex">
                                <span class="d-flex flex-column align-items-end justify-content-space-between">
                                    <span
                                        class="price fw-bold">
                                        ${{price_format($sellCertificate['price_per_unit']) }}
                                    </span>
                                    <!-- <span class="statistic-price statistic-decrease d-flex  ms-1">
                                        @if($sellCertificate['price_average'] <= 0)
                                        <svg class="icon icon-decrease me-1" width="16" height="16">
                                            <use href="{{asset('/img/icons.svg#icon-decrease')}}"></use>
                                            </svg> {{$sellCertificate['price_average']}}%

                                    @else
                                        <svg class="icon icon-increase me-1" width="16" height="16">
                                            <use href="{{asset('/img/icons.svg#icon-increase')}}"></use>
                                            </svg> {{$sellCertificate['price_average']}}%

                                    @endif
                                    </span> -->
                                </span>
                                    <!-- <span class="small-graph ms-2">
                                    <canvas class="small-graph-buy" data-chart="{{json_encode($sellCertificate->chart)}}" data-max-val="{{$sellCertificate->maxValue}}"></canvas>
                                </span> -->
                                </div>
                            </td>
                            <td x-data="{}">
                                <div class="d-flex">
                                    <a href="javascript:void(0);"
                                       wire:click="followCredit({{ $sellCertificate->id }})"
                                       class=" me-2 w-10"
                                       type="button"
                                       :disabled="{{$disable_id==$sellCertificate->id?'true':'false'}}"
                                    >
                                        <i class="fa fa-thumbs-up @if($sellCertificate->followers()->where('user_id',auth()->id())->first()) icon-dark @else icon-blue @endif"
                                           aria-hidden="true"></i>
                                    </a>
                                    <a href="javascript:void(0);"
                                       wire:click="openModal({{ $sellCertificate['id'] }})"
                                       class="button-green buy-table me-2 buy-certitficate"
                                       type="button"
                                       :disabled="{{$disable_id==$sellCertificate['id']?'true':'false'}}"
                                    >
                                        {{ __('Buy') }}
                                    </a>
                                    <a href="{{route('buy.show.certificate', $sellCertificate['id'])}}"
                                       class="button-secondary buy-table me-2"
                                       type="button">
                                        {{ __('View') }}
                                    </a>
                                    <div class="dropdown button-grey dropdown-grey mx-2 col-auto">
                                        <button class="btn dropdown-toggle bs-placeholder btn-light show"
                                                type="button" id="genderDropdown" data-bs-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="genderDropdown">
                                            <a class="dropdown-item cursor-pointer"
                                               wire:click="openPriceAlertModal('{{$sellCertificate['id']}}')">{{ __('Set Price Alert') }}</a>
                                        </div>
                                    </div>
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

        @push('modals')
            @livewire('buy.buy-bid-modal')
            @livewire('buy.price-alert-modal')
        @endpush
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
            <h4 class="fw-bold p-0 mb-4"> {{ __('Trending') }}  </h4>
        </div>
        @forelse ($lists as $sellCertificate)
            <div class="card-el p-3 mb-3">
                <div class="card-header d-flex justify-content-between">
                    <a href="{{route('buy.show.certificate',['id' => $sellCertificate['id']])}}" class="text-decoration-none text-black">
                        <div class="d-flex align-items-center">
                            <svg class="icon {{$sellCertificate['certificate']['project_type']['image']}} me-2"
                                 width="32" height="32">
                                <use
                                    href="./img/icons.svg#{{$sellCertificate['certificate']['project_type']['image']}}">
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

                        <a href="javascript:void(0);"
                           wire:click="followCredit({{ $sellCertificate->id }})"
                           class=" me-2 w-10"
                           type="button"
                           :disabled="{{$disable_id==$sellCertificate->id?'true':'false'}}"
                        >
                            <i class="fa fa-thumbs-up @if($sellCertificate->followers()->where('user_id',auth()->id())->first()) icon-dark @else icon-blue @endif"
                               aria-hidden="true"></i>
                        </a>
                        <a href="javascript:void(0);"
                           wire:click="openModal({{ $sellCertificate['id'] }})"
                           class="button-green buy-table me-2 buy-certitficate"
                           type="button"
                           :disabled="{{$disable_id==$sellCertificate['id']?'true':'false'}}"
                        >
                            {{ __('Buy') }}
                        </a>
                        <a href="{{route('buy.show.certificate', $sellCertificate['id'])}}"
                           class="button-secondary buy-table me-2"
                           type="button">
                            {{ __('View') }}
                        </a>
                        <div class="dropdown button-grey dropdown-grey mx-2 col-auto">
                            <button class="btn dropdown-toggle bs-placeholder btn-light show"
                                    type="button" id="genderDropdown" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                            </button>
                            <div class="dropdown-menu" aria-labelledby="genderDropdown">
                                <a class="dropdown-item cursor-pointer"
                                   wire:click="openPriceAlertModal('{{$sellCertificate['id']}}')">{{ __('Set Price Alert') }}</a>
                            </div>
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
    @push('modals')
        @livewire("profile.detail.user.resend-verification-s-m-s-modal",['user'=>$user])
    @endpush
</div>
@push('scripts')
    <script>
        let myCharts = [];
        // function setDate() {
        //     let selectedDate = document.getElementById('my_hidden_input').value;
        //     Livewire.emit('setSelectedDate',selectedDate);
        // }
        function refreshGraph() {
            if (myCharts.length) {
                for (let i = 0; i < myCharts.length; i++) {
                    myCharts[i].destroy();
                }
            }
            if ($('canvas.small-graph-buy').length) {
                $('canvas.small-graph-buy').each(function (index, el) {
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
                .on('changed.bs.select', '.selectpicker-class', function (e) {
                    // Livewire.emit('getBuyToggleData');
                });
            Livewire.hook('message.processed', () => {
                refreshGraph();
            });
        });
    </script>

@endpush


