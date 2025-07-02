<div>
    <div class="row col-12 p-0 m-0">
        <div class="bg-white block-main row m-0 p-0 h-100 pb-32 flex-column showCertificateBlcok">
            <div class="col-12 p-32 row m-0">
                <nav aria-label="breadcrumb" class="mb-40">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{url('admin/certificates')}}">Carbon Credits</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{$certificate->name}}</li>
                    </ol>


                    @if($certificate->status == 1)
                        <div class="status-button pending fw-normal ms-2">
                            Pending approval
                        </div>
                    @elseif($certificate->status == 2)
                        <div class="status-button approved fw-normal ms-2">
                            Approved
                        </div>
                    @elseif($certificate->status == 3)
                        <div class="status-button onsell fw-normal ms-2">
                            On Sell
                        </div>
                    @else
                        <div class="status-button notapproved fw-normal ms-2">
                            Declined
                        </div>
                    @endif
                </nav>
                <div class="col-auto d-flex align-items-center">
                    <svg width="48" height="48" class="icon icon-Forest-ERB">
                        <use href="{{asset('img/icons.svg#'.$certificate->project_type->image_icon)}}"></use>
                    </svg>
                    <div class="info-main d-flex flex-column">
                        <span class="fw-bold">{{$certificate->project_type->type}}</span>
                        <span class="main-info d-flex align-items-baseline">
                                        <span class="price">${{price_format($price)}}</span>
                                        <span class="statistic-price statistic-decrease d-flex  ms-1">
{{--                                            <svg class="icon icon-decrease me-1" width="16" height="16">--}}
                                            {{--                                                @if($differenceType == 'inc')--}}
                                            {{--                                                    <use href="{{asset('/img/icons.svg#icon-increase')}}"></use>--}}
                                            {{--                                                @else--}}
                                            {{--                                                    <use href="{{asset('/img/icons.svg#icon-decrease')}}"></use>--}}
                                            {{--                                                @endif--}}
                                            {{--                                            </svg> @if($differenceType == 'inc')--}}
                                            {{--                                                +--}}
                                            {{--                                            @else--}}
                                            {{--                                                ---}}
                                            {{--                                            @endif--}}
                                            {{--                                            {{price_format($priceDifference)}}%--}}

                                        </span>



                                        <span
                                            class="statistic-price d-flex  ms-3">Quantity : {{$certificate->quantity}}</span>



                                    </span>
                    </div>

                </div>
                <div class="edit-info-sell ms-0 ms-md-2 align-self-end fs-16 col-auto">
                    <span>
                        Country:<b>{{$certificate->country->name}}</b>
                    </span>
                </div>
                <div class="edit-info-sell ms-0 ms-md-2 align-self-end fs-16 col-auto">
                    <span>
                        Project Year:<b>{{$certificate->project_year}}</b>
                    </span>
                </div>
                @if($certificate->status != 2 || $certificate->status != 3)
                    <div class="col-3 col-xl-3 ms-auto align-items-end d-flex">
                        <a href="javascript:void(0)"
                           class="button-green approveCertificateBtn w-100"
                           wire:click="approveCertificate({{$certificate->id}})"
                           type="button">
                            Approve Carbon Credit
                        </a>
                    </div>
                @endif

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
{{--                               aria-selected="true">Overview</a>--}}
{{--                        </li>--}}
{{--                        <li class="nav-item col text-center p-0" role="presentation">--}}
{{--                            <a class="nav-link" id="soon-tab" disabled="disabled" data-bs-toggle="tab"--}}
{{--                               data-bs-target="#soon"--}}
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
{{--                                                wire:click="$emit('certificate-selected',['{{ $certificateId }}','14'])"--}}
{{--                                                class="btn fiterBtn @if($maxDays == 14) active @endif " data-days="14">2--}}
{{--                                            Weeks--}}
{{--                                        </button>--}}
{{--                                        <button type="button"--}}
{{--                                                wire:click="$emit('certificate-selected',['{{ $certificateId }}','30'])"--}}
{{--                                                class="btn fiterBtn @if($maxDays == 30) active @endif" data-days="30">1--}}
{{--                                            Month--}}
{{--                                        </button>--}}
{{--                                        <button type="button"--}}
{{--                                                wire:click="$emit('certificate-selected',['{{ $certificateId }}','180'])"--}}
{{--                                                class="btn fiterBtn @if($maxDays == 180) active @endif" data-days="180">--}}
{{--                                            6 Month--}}
{{--                                        </button>--}}
{{--                                        <button type="button"--}}
{{--                                                wire:click="$emit('certificate-selected',['{{ $certificateId }}','365'])"--}}
{{--                                                class="btn fiterBtn @if($maxDays == 365) active @endif" data-days="365">--}}
{{--                                            1 Year--}}
{{--                                        </button>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <canvas id="mainChartDetail" class="p-0"></canvas>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="tab-pane fade row" id="soon" role="tabpanel" aria-labelledby="soon-tab">--}}
{{--                            Coming Soon--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="col gap-5 d-flex flex-column p-0 h-100 ">
            <div class="bg-white block-main row m-0  h-100">

                <div class="col-12 p-0">
                    <div class="row mb-30 fs-16 buy-desc">
                        <label for="project_name"
                               class="form-label p-0 black-color">{{ __('Carbon Credit Photos') }}</label>
                        <label
                            class="drop-area {{ !$errors->has('file_path')?(($file_path)?'full drop-area-cover':(!blank($currentImage)?'full':'')):'' }}">
                            <span><span class="info">
                                    <svg class="icon icon-upload-cloud" width="32" height="32">
                                        <use href="{{asset('img/icons.svg#icon-upload-cloud')}}"></use>
                                    </svg>
                                    <b>upload a file </b>or drag and
                                    drop</span>
                                <span class="limit">PNG, JPG, GIF up to 10MB</span>
                            </span>
                            <input type="file" wire:model.defer="file_path" id="fileElem"
                                   class="{{$errors->has('file_path')?'error':''}}" accept="image/*" multiple>
                            <span class="gallery">
                                @if (!$errors->has('file_path'))
                                    <div class="image-gallery m-auto text-center">
                                        <img src="{{ $currentImage }}" alt="Image">
                                    </div>
                                @endif
                            </span>
                        </label>
                        <div class="row">
                            @if(!blank($currentImage))
                                <button class="button-green btn-xs"
                                        wire:click="prevImage" {{ $currentImageIndex == 0 ? 'disabled' : '' }}>
                                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                                </button>

                                <button class="button-green"
                                        wire:click="nextImage" {{ $currentImageIndex == ($total_files - 1) ? 'disabled' : '' }}>
                                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                                </button>
                            @endif
                        </div>
                        <x-jet-input-error for="file_path" class="mt-2" />
                    </div>
                    <div class="row mb-30 fs-16 buy-desc">
                        <label for="project_name"
                               class="form-label p-0 black-color">{{ __('Carbon Credit Name') }}</label>
                        <input type="text" class="form-control default" wire:model.defer="name" id="name"
                               value="{{$certificate->name}}">
                        <x-jet-input-error for="name" class="mt-2" />
                    </div>
                    <div class="row mb-30 fs-16 buy-desc">
                        <label for="certificate_description"
                               class="form-label p-0 black-color">{{ __('Carbon Credit Description') }}</label>
                        <textarea class="form-control default" wire:model.defer="description" rows="10"
                                  id="description">{{$certificate->description}}</textarea>
                        <x-jet-input-error for="description" class="mt-2" />
                    </div>
                    <div class="row mb-30 fs-16 buy-desc">
                        <x-jet-label for="lattitude" class="form-label p-0 black-color" value="{{ __('Latitude') }}"/>
                        <x-jet-input id="lattitude" type="number" maxlength="16" class="form-control default"
                                     wire:model.defer="lattitude" autocomplete="lattitude"/>
                        <x-jet-input-error for="lattitude" class="mt-2"/>
                    </div>
                    <div class="row mb-30 fs-16 buy-desc">
                        <x-jet-label for="longitude" class="form-label p-0 black-color" value="{{ __('Longitude') }}"/>
                        <x-jet-input id="longitude" type="number" onKeyPress="if(this.value.length==16) return false;"
                                     maxlength="16" class="form-control default" wire:model.defer="longitude"
                                     autocomplete="longitude"/>
                        <x-jet-input-error for="longitude" class="mt-2"/>
                    </div>
                    <div class="row mb-30 fs-16 buy-desc">
                        <x-jet-label for="verify_by" class="form-label p-0 black-color" value="{{ __('Verified By') }}"/>
                        <x-jet-input id="verified_by" type="text" maxlength="16" class="form-control default"
                                     wire:model.defer="verify_by" autocomplete="verify_by"/>
                        <x-jet-input-error for="verify_by" class="mt-2"/>
                    </div>
                    <div class="row mb-30 fs-16 buy-desc">
                        <x-jet-label for="registry_id" class="form-label p-0 black-color"
                                     value="{{ __('Registry Id') }}"/>
                        <x-jet-input id="registry_id" type="text" maxlength="16" class="form-control default"
                                     wire:model.defer="registry_id" autocomplete="registry_id"/>
                        <x-jet-input-error for="registry_id" class="mt-2"/>
                    </div>
                    {{--                    <div class="row mb-30 fs-16 buy-desc">--}}
                    {{--                        <label class="drop-area {{ !$errors->has('file_path')?(($file_path)?'full':($certificate->file_path?'full':'')):'' }}">--}}
                    {{--                            <span><span class="info">--}}
                    {{--                                    <svg class="icon icon-upload-cloud" width="32" height="32">--}}
                    {{--                                        <use href="{{asset('img/icons.svg#icon-upload-cloud')}}"></use>--}}
                    {{--                                    </svg>--}}
                    {{--                                    <b>upload a file </b>or drag and--}}
                    {{--                                    drop</span>--}}
                    {{--                                <span class="limit">PNG, JPG, GIF up to 10MB</span>--}}
                    {{--                            </span>--}}
                    {{--                            <input type="file" wire:model.defer="file_path" id="fileElem" class="{{$errors->has('file_path')?'error':''}}" accept="image/*">--}}
                    {{--                            <span class="gallery">--}}
                    {{--                                @if (!$errors->has('file_path'))--}}
                    {{--                                    @if($file_path)--}}
                    {{--                                        <img src="{{$file_path->temporaryUrl()}}" alt="Preview">--}}
                    {{--                                    @elseif($certificate->file_path)--}}
                    {{--                                        <img src="{{url($certificate->file_path)}}" alt="Preview">--}}
                    {{--                                    @endif--}}
                    {{--                                @endif--}}
                    {{--                            </span>--}}
                    {{--                        </label>--}}
                    {{--                        <x-jet-input-error for="file_path" class="mt-2" />--}}
                    {{--                    </div>--}}

                    <div class="row mb-30 mt-30">
                        <button type="button" class="button-green w-100  button-next"
                                wire:click="storeFilePath">Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-5 d-flex flex-column p-0 h-100">
            <div class="bg-white block-main row m-0  h-100">
                <div class="m-0 p-0">
                    <div class="row mb-30  fs-16 buy-desc">
                        <label class="align-self-end fs-24">Carbon Credit Location</label>
                        <p class="text-justify mt-1"><b>Latitude:</b>{{$certificate->lattitude}} , <b>Longitude:</b> {{$certificate->longitude}}
                        </p>
                    </div>
                    <div class="row mb-30  fs-16 buy-desc">
                        <label class="align-self-end fs-24 mb-2">Carbon Credit Details</label>
                        <div class="col-4 d-flex flex-col mb-2">
                            <label class="fs-20">Vintage</label>
                            <label>{{$certificate->vintage}}</label>
                        </div>
                        <div class="col-4 d-flex flex-col mb-2">
                            <label class="fs-20">Verified By</label>
                            <label>{{$certificate->verify_by}}</label>
                        </div>
                        <div class="col-4 d-flex flex-col mb-2">
                            <label class="fs-20">Acres</label>
                            <label>{{$certificate->total_size}}</label>
                        </div>
                        <div class="col-4 d-flex flex-col">
                            <label class="fs-20">Registry Id</label>
                            <label>{{$certificate->registry_id}}</label>
                        </div>
                        <div class="col-4 d-flex flex-col">
                            <label class="fs-20">Carbon Credit</label>
                            <a href="{{$certificate->link_to_certificate}}" class="text-decoration-none" target="_blank">View</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>

        document.addEventListener('livewire:load', function () {

            let maxDay = 14;
            let datas = [
                { x: 0, y: 30, date: '1/1/22' },
                { x: 1, y: 18, date: '2/1/22' },
                { x: 2, y: 39, date: '3/1/22' },
                { x: 3, y: 70, date: '4/1/22' },
                { x: 4, y: 79, date: '5/1/22' },
                { x: 5, y: 65, date: '6/1/22' },
                { x: 6, y: 90, date: '7/1/22' },
                { x: 7, y: 30, date: '8/1/22' },
                { x: 8, y: 60, date: '9/1/22' },
                { x: 9, y: 60, date: '10/1/22' },
                { x: 10, y: 50, date: '11/1/22' },
                { x: 11, y: 79, date: '12/1/22' },
                { x: 12, y: 65, date: '13/1/22' },
                { x: 13, y: 90, date: '14/1/22' },
            ];

            var ctx = document.getElementById('mainChartDetail').getContext('2d');
            const footer = tooltipItems => {
                // console.log(tooltipItems[0].label);
                /*if(tooltipItems[0].dataset.data.length == 14 || tooltipItems[0].dataset.data.length == 30){
                    return `${labels1[tooltipItems[0].label % 7]}, ${tooltipItems[0].raw.date}`;
                }else{
                    return `${labels2[tooltipItems[0].label % 12]}, ${tooltipItems[0].raw.date}`;
                }*/
                return `${ tooltipItems[0].label }, ${ tooltipItems[0].raw.date }`;

            };

            const title = tooltipItems => {
                return '$' + tooltipItems[0].formattedValue;
            };
            //let labels1 = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
            let labels = @json($labels);
            //let labels2 = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            //let labels2 = @json($labels);
            console.log(@json($labels));
            console.log(@json($dataset));
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
                                stepSize: {{round($maxValue / 10)}},
                                callback: function (value, index, ticks) {
                                    return '$' + value;
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
                console.log(getData.data);
                labels = getData.labels;
                chart.data.datasets[0].data = getData.data;
                chart.data.labels = getData.labels;
                chart.options.scales.y.max = getData.maxValue;
                chart.options.scales.y.ticks.stepSize = getData.stepSize;
                chart.update();
            });

        });

    </script>

@endpush
