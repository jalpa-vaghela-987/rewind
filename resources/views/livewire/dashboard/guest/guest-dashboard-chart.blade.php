<div class="col-12 col-lg block-more-half">
    <div class="row col mb-24">
        <h6 class="fw-bold">Trending</h6>
    </div>
    <div class="row col d-flex align-items-end mb-70">
        <div class="col-auto d-flex align-items-center">
            <svg class="icon icon-Forest-ERB me-3" width="48" height="48">
                <use href="{{asset('img/icons.svg#icon-Forest-ERB')}}"></use>
            </svg>
            <div class="info-main d-flex flex-column">
                <span class="subtitle fw-bold">{{$name}}</span>
                <span class="main-info d-flex alig-items-baseline">
                    <span class="price">{{ $price?'$'.price_format($price):''}}</span>
                    <span class="statistic-price statistic-decrease d-flex  ms-1">
                        <svg class="icon icon-decrease me-1" width="16" height="16">
                            @if($differenceType == 'inc')
                                <use href="{{asset('/img/icons.svg#icon-increase')}}"></use>
                            @else
                                <use href="{{asset('/img/icons.svg#icon-decrease')}}"></use>
                            @endif
                        </svg>
                        @if($differenceType == 'inc') + @else - @endif
                        {{price_format($priceDifference)}}%</span>
                </span>
            </div>
        </div>
        <div class="ms-auto col-auto">
            <div class="btn-group btn-row" role="group" aria-label="Basic example">
                <button type="button" wire:click="$emit('certificate-selected',['{{ $certificateId }}','14'])" class="btn fiterBtn @if($maxDays == 14) active @endif " data-days="14">2 Weeks</button>
                <button type="button" wire:click="$emit('certificate-selected',['{{ $certificateId }}','30'])" class="btn fiterBtn @if($maxDays == 30) active @endif" data-days="30">1 Month</button>
                <button type="button" wire:click="$emit('certificate-selected',['{{ $certificateId }}','180'])" class="btn fiterBtn @if($maxDays == 180) active @endif" data-days="180">6 Month</button>
                <button type="button" wire:click="$emit('certificate-selected',['{{ $certificateId }}','365'])" class="btn fiterBtn @if($maxDays == 365) active @endif" data-days="365">1 Year</button>
            </div>
        </div>
    </div>
    <canvas id="mainChartInner"></canvas>
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

            var ctx = document.getElementById('mainChartInner').getContext('2d');
            const footer = tooltipItems => {
                // console.log(tooltipItems[0].label);
                /*if(tooltipItems[0].dataset.data.length == 14 || tooltipItems[0].dataset.data.length == 30){
                    return `${labels1[tooltipItems[0].label % 7]}, ${tooltipItems[0].raw.date}`;
                }else{
                    return `${labels2[tooltipItems[0].label % 12]}, ${tooltipItems[0].raw.date}`;
                }*/
                return `${tooltipItems[0].label}, ${tooltipItems[0].raw.date}`;

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
                            borderColor: '#55D168',
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
                                    if(ticks.length == 14 || ticks.length == 30){
                                        return labels[value % 7];
                                    }else{
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

        function toggleDateModal() {
            Livewire.emit('toggleDateModal');
        }

        function setDate() {
            let selectedDate = document.getElementById('my_hidden_input').value;
            Livewire.emit('setSelectedDate',selectedDate);
        }
    </script>
@endpush
