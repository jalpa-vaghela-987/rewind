<div class="row w-100 m-0 pt-0 gap-4">
    <div class="row col-12 p-0 m-0">
        <div class="bg-white block-main row m-0 p-0 h-100 pb-32 flex-column">
            <div class="col-12 p-32 row m-0">
                <nav aria-label="breadcrumb" class="mb-40">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{route('buy')}}">Buy</a></li>
                        <li class="breadcrumb-item active"
                            aria-current="page">{{$sellCertificate->certificate->name}}</li>
                    </ol>
                </nav>
                <div class="col-auto d-flex align-items-center">
                    <svg class="icon icon-Forest-ERB me-3" width="48" height="48">
                        <use
                            href="{{asset('img/icons.svg#'.$sellCertificate->certificate->project_type->image_icon)}}"></use>
                    </svg>
                    <div class="info-main d-flex flex-column">
                        <span class="fw-bold">{{$sellCertificate->certificate->project_type->type}}</span>
                        <span class="main-info d-flex align-items-baseline">
                                <span class="price">${{ price_format($sellCertificate->price_per_unit)}}</span>
                                <?php /*<span class="statistic-price statistic-decrease d-flex  ms-1">
                                    @if($sellCertificate->price_average <= 0)
                                        <svg class="icon icon-decrease me-1" width="16" height="16">
                                            <use href="{{asset('/img/icons.svg#icon-decrease')}}"></use>
                                        </svg> {{$sellCertificate->price_average}}%
                                    @else
                                        <svg class="icon icon-increase me-1" width="16" height="16">
                                                    <use href="{{asset('/img/icons.svg#icon-increase')}}"></use>
                                                </svg> {{$sellCertificate->price_average}}%
                                    @endif
                                </span>*/?>
                        <!-- <span class="statistic-price statistic-increase d-flex ms-1">
                                                                <svg class="icon icon-increase me-1" width="16" height="16">
                                                                    <use href="./img/icons.svg#icon-increase"></use>
                                                                </svg> +0.38%</span> -->
                            </span>
                    </div>
                </div>
                <div class="edit-info-sell ms-0 ms-md-2 align-self-end fs-16 col-auto">
                    <span>
                        Country:<b>{{$sellCertificate->certificate->country->name}}</b>
                    </span>
                </div>
                <div class="edit-info-sell ms-0 ms-md-2 align-self-end fs-16 col-auto">
                    <span>
                        Project Year:<b>{{$sellCertificate->certificate->project_year}}</b>
                    </span>
                </div>
                <div class="col-3 col-xl-2 ms-auto align-items-end d-flex">
                    <a href="javascript:void(0)" class="button-green w-100"
                       wire:click.prevent="openModal({{$sellCertificate->id}})" type="button">Buy</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row col-12 p-0 m-0 gap-4 mh-75vh">
{{--        <div class="col gap-4 d-flex flex-column p-0 h-100">--}}
{{--            <div class="bg-white block-main row m-0 h-100">--}}
{{--                <div class="col-12 p-0">--}}
{{--                    <ul class="nav basic-style row mx-0 mb-30" id="overviewTabs" role="tablist">--}}
{{--                        <li class="nav-item col text-center p-0" role="presentation">--}}
{{--                            <a class="nav-link active" id="overview-tab" data-bs-toggle="tab"--}}
{{--                               data-bs-target="#overview" type="button" role="tab" aria-controls="overview"--}}
{{--                               aria-selected="true">Overview </a>--}}
{{--                        </li>--}}
{{--                        <li class="nav-item col text-center p-0" role="presentation">--}}
{{--                            <a class="nav-link" id="soon-tab" data-bs-toggle="tab" data-bs-target="#soon"--}}
{{--                               type="button" role="tab" aria-controls="soon" aria-selected="false">Coming--}}
{{--                                soon</a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                    <div class="tab-content mb-24" id="overviewTabsContent">--}}
{{--                        <div class="tab-pane fade show active row" id="overview" role="tabpanel"--}}
{{--                             aria-labelledby="overview-tab">--}}
{{--                            <div class="col-12 row align-items-center  d-flex m-0 justify-content-center">--}}
{{--                                <div class="ms-auto col-auto mt-90 mb-20">--}}
{{--                                    <div class="btn-group btn-row" role="group" aria-label="Basic example">--}}
{{--                                        <button type="button"--}}
{{--                                                wire:click.prevent="$emit('certificate-selected',['{{ $sellCertificate->id }}','14'])"--}}
{{--                                                class="btn fiterBtn @if($maxDays == 14) active @endif " data-days="14">2--}}
{{--                                            Weeks--}}
{{--                                        </button>--}}
{{--                                        <button type="button"--}}
{{--                                                wire:click.prevent="$emit('certificate-selected',['{{ $sellCertificate->id }}','30'])"--}}
{{--                                                class="btn fiterBtn @if($maxDays == 30) active @endif" data-days="30">1--}}
{{--                                            Month--}}
{{--                                        </button>--}}
{{--                                        <button type="button"--}}
{{--                                                wire:click.prevent="$emit('certificate-selected',['{{ $sellCertificate->id }}','180'])"--}}
{{--                                                class="btn fiterBtn @if($maxDays == 180) active @endif" data-days="180">--}}
{{--                                            6 Month--}}
{{--                                        </button>--}}
{{--                                        <button type="button"--}}
{{--                                                wire:click.prevent="$emit('certificate-selected',['{{ $sellCertificate->id }}','365'])"--}}
{{--                                                class="btn fiterBtn @if($maxDays == 365) active @endif" data-days="365">--}}
{{--                                            1 Year--}}
{{--                                        </button>--}}

{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <canvas id="mainChartInner" class="p-0"></canvas>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="tab-pane fade row" id="soon" role="tabpanel" aria-labelledby="soon-tab">--}}
{{--                            Coming Soon--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="col-12 col-lg-5 gap-4 d-flex flex-column p-0">--}}
{{--            <div class="bg-white block-main row m-0">--}}
{{--                <div class="col-12 p-0">--}}
{{--                    <div class="row mb-30">--}}
{{--                        <h5 class="fw-bold">{{$sellCertificate->certificate->name}}</h5>--}}
{{--                    </div>--}}
{{--                    <div class="row mb-30 fs-16 buy-desc">--}}
{{--                        <p>{{$sellCertificate->certificate->approving_body}}</p>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="bg-white block-main row m-0  h-100">--}}
{{--                    <div class="m-0 p-0">--}}
{{--                        <div class="gallery h-50 p-0">--}}
{{--                            @if (!$errors->has('file_path'))--}}
{{--                                @if($total_files > 0)--}}
{{--                                    <img src="{{ $currentImage }}" alt="buy-certificate-image">--}}
{{--                                @else--}}
{{--                                    <img--}}
{{--                                        src="https://picsum.photos/id/19/960/960"--}}
{{--                                        alt="buy-certificate-image">--}}
{{--                                @endif--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-12">--}}
{{--                        @if(!blank($currentImage))--}}
{{--                            <div class="btn-group">--}}
{{--                                <button class="button-green btn-xs"--}}
{{--                                        wire:click="prevImage" {{ $currentImageIndex == 0 ? 'disabled' : '' }}>--}}
{{--                                    <i class="fa fa-chevron-left" aria-hidden="true"></i>--}}
{{--                                </button>--}}

{{--                                <button class="button-green"--}}
{{--                                        wire:click="nextImage" {{ $currentImageIndex == ($total_files - 1) ? 'disabled' : '' }}>--}}
{{--                                    <i class="fa fa-chevron-right" aria-hidden="true"></i>--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                        @endif--}}
{{--                    </div>--}}
{{--                    @endif--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

        <div class="col gap-5 d-flex flex-column p-0">
            <div class="bg-white block-main row m-0 h-100">
                <div class="m-0 p-0">
                    <div class="d-flex justify-content-between">
                        <label class="align-self-end fs-24 ">Carbon Credit Photo Gallery</label>
                        @if(!blank($currentImage))
                            <div class="btn-group">
                                <button class="button-green btn-xs"
                                        wire:click="prevImage" {{ $currentImageIndex == 0 ? 'disabled' : '' }}>
                                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                                </button>

                                <button class="button-green"
                                        wire:click="nextImage" {{ $currentImageIndex == ($total_files - 1) ? 'disabled' : '' }}>
                                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                                </button>
                            </div>
                        @endif

                    </div>

                    @if(!blank($currentImage))
                        <div class="gallery p-0 mt-2">
                            @if (!$errors->has('file_path'))
                                @if($total_files > 0)
                                    <img src="{{ $currentImage }}" alt="buy-certificate-image" class="certificate_image">
                                @else
                                    <img
                                        src="https://picsum.photos/id/19/960/960"
                                        alt="buy-certificate-image">
                                @endif
                            @endif
                        </div>
                    @endif
                </div>
                <div class="m-0 p-0 mt-4">
                    <div class="mb-30  fs-16 buy-desc">
                        <label class="align-self-end fs-24">Carbon Credit Description</label>
                        <p class="text-justify">{{$sellCertificate->certificate->description}} </p>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-12 col-lg-5 d-flex flex-column p-0 h-100">
            <div class="bg-white block-main row m-0  h-100">
                <div class="m-0 p-0">
                    <div class="row mb-30  fs-16 buy-desc">
                        <label class="align-self-end fs-24">Carbon Credit Location</label>
                        <p class="text-justify mt-1"><b>Latitude:</b>{{$sellCertificate->certificate->lattitude}} , <b>Longitude:</b> {{$sellCertificate->certificate->longitude}}
                        </p>
                    </div>
                    <div class="row mb-30  fs-16 buy-desc">
                        <label class="align-self-end fs-24 mb-2">Carbon Credit Details</label>
                        <div class="col-4 d-flex flex-col mb-2">
                            <label class="fs-20"><b>Vintage</b></label>
                            <label>{{$sellCertificate->certificate->vintage}}</label>
                        </div>
                        <div class="col-4 d-flex flex-col mb-2">
                            <label class="fs-20"><b>Verified By</b></label>
                            <label>{{$sellCertificate->certificate->verify_by}}</label>
                        </div>
                        <div class="col-4 d-flex flex-col mb-2">
                            <label class="fs-20"><b>Acres</b></label>
                            <label>{{$sellCertificate->certificate->total_size}}</label>
                        </div>
                        <div class="col-4 d-flex flex-col">
                            <label class="fs-20"><b>Registry Id</b></label>
                            <label>{{$sellCertificate->certificate->registry_id}}</label>
                        </div>
                        <div class="col-8 d-flex flex-col">
                            <label class="fs-20"><b>Carbon Credit</b></label>
                            <a href="{{$sellCertificate->certificate->link_to_certificate}}" class="text-decoration-none" target="_blank">View</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @push('modals')
            @livewire('buy.buy-bid-modal')
        @endpush
    <!-- Card Modal END -->

        @push('modals')
            <x-jet-modal class="modal fade" id="dateModal" tabindex="-1" wire:model="showDateModal">
                <div class="modal-dialog modal-dialog-centered modal-sm-date">
                    <div class="modal-content p-4">
                        <div class="modal-body p-0 row">
                            <div class="row col-12 p-0 m-0">
                                <h4 class="black-color col fw-bold mb-2">
                                    Select expiration date
                                </h4>
                                <button type="button"
                                        onclick="toggleDateModal()"
                                        class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2"
                                ></button>
                            </div>
                            <div class="row col-12 p-0 m-0 mb-3">
                                <div class="" id="datepicker-expiration" data-provide="datepicker"></div>
                                <input type="hidden" id="my_hidden_input">

                            </div>
                            <div class="row col-12 m-0">
                                <a class="button-green w-100 button-send" id="confirmation-btn"
                                   onclick="setDate()" href="#"
                                   data-input="#buyModal #Expiration">Confirm
                                    Expiration Date</a>
                            </div>
                        </div>
                    </div>
                </div>
            </x-jet-modal>
            @livewire("profile.detail.user.resend-verification-s-m-s-modal",['user'=>$user])
        @endpush
    </div>
    @push('scripts')
        <script>
            document.addEventListener('livewire:load', function () {
                let maxDay = 14;

                var ctx = document.getElementById('mainChartInner').getContext('2d');
                const footer = tooltipItems => {
                    return `${ tooltipItems[0].label }, ${ tooltipItems[0].raw.date }`;
                };

                const title = tooltipItems => {
                    return '$' + tooltipItems[0].formattedValue;
                };

                let labels = @json($labels);
                var gradient = ctx.createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, 'rgba(205,242,205,1)');
                gradient.addColorStop(1, 'rgba(205,242,205,0)');


                let mainSettings = {
                    type: 'line',
                    data: {
                        labels: @json($labels),
                        datasets: [
                            {
                                fill: true,
                                backgroundColor: gradient,
                                borderColor: '#1d7db2',
                                borderWidth: 3,
                                pointRadius: 0,
                                pointHoverRadius: 10,
                                hitRadius: 5,
                                data: @json($dataset),

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
                            tooltip: {
                                backgroundColor: '#fff',
                                titleFont: {
                                    weight: 'bold',
                                    size: 14,
                                    family: "'Roboto', 'Helvetica', 'Arial', sans-serif",
                                },
                                titleColor: '#000',
                                footerFont: {
                                    weight: 'normal',
                                    size: 12,
                                    family: "'Roboto', 'Helvetica', 'Arial', sans-serif",
                                },
                                footerColor: '#000',
                                displayColors: false,
                                bodyFont: { size: 0 },
                                padding: 11,
                                cornerRadius: 8,
                                borderColor: '#E1E1E1',
                                borderWidth: 1,
                                callbacks: {
                                    footer: footer,
                                    title: title,
                                },
                            },
                            parsing: {
                                xAxisKey: 'x',
                            },
                        },
                        responsive: true,
                        tension: 0.5,
                        scales: {
                            y: {
                                min: 0,
                                max: {{$maxValue}},
                                ticks: {
                                    stepSize: {{$stepSize}},
                                    callback: function (value, index, ticks) {
                                        return '$' + Math.round(value);
                                    },
                                    color: '#000000',
                                    font: {
                                        size: 14,
                                        family: "'Roboto', 'Helvetica', 'Arial', sans-serif",
                                    },
                                },
                                border: {
                                    color: '#ff000000',
                                },
                                grid: {
                                    color: '#E8E9EA',
                                    borderCapStyle: 'round',
                                    borderDash: [9, 9],
                                    tickColor: '#ff000000',
                                    borderWidth: 0,
                                    lineWidth: 2,
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
                                    color: '#000000',
                                    font: {
                                        size: 14,
                                        family: "'Roboto', 'Helvetica', 'Arial', sans-serif",
                                    },
                                    callback: function (value, index, ticks) {
                                        if ( ticks.length == 14 || ticks.length == 30 ) {
                                            return labels[value % 7];
                                        } else {
                                            return labels[value % 12];
                                        }
                                        //return labels[value];
                                    },
                                },
                            },
                        },
                    },
                };
                const chart = new Chart(ctx, mainSettings);

                Livewire.on('updateChart', getData => {
                    labels = getData.labels;
                    chart.data.datasets[0].data = getData.data;
                    chart.data.labels = getData.labels;
                    chart.options.scales.y.max = getData.maxValue;
                    chart.options.scales.y.ticks.stepSize = getData.stepSize;
                    chart.update();
                });
            });

            function toggleDateModal() {
                Livewire.emit('toggleDateModal');
            }

            function setDate() {
                let selectedDate = document.getElementById('my_hidden_input').value;
                Livewire.emit('setSelectedDate', selectedDate);
            }
        </script>
@endpush
