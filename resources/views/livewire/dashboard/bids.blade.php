<div>
    @if($certificates->count() > 0)
        <div class="bg-white block-main row m-0 p-0 pt-3  table-container h-100  d-none d-sm-flex mh-75vh overflow-auto">
            <div class="col-12 p-0">
                <div class="row col mb-24 ms-4">
                    <h6 class="fw-bold p-0">Bids</h6>
                </div>
                <div class="table-container">
                    <x-data-table.table :model="$certificates" :columns="[]" :wantSearching="true" :dateFilter="true">
                        <x-slot name="head">
                            <tr>
                                <th data-filter-control-placeholder="Name" data-field="Name" style="width: 200px">Name
                                </th>
                                <th data-filter-control-placeholder="Value" data-field="Value">Value</th>
                                <th data-filter-control-placeholder="BidPrice" data-field="Value">Bid Price</th>
                            </tr>
                        </x-slot>
                        <x-slot name="body">
                            @forelse ($certificates as $certificate)
                                <tr class="even:bg-white odd:bg-gray-50">
                                    <td x-data="{}">
                                        <div class="d-flex align-items-center">
                                            <svg class="icon icon-Forest-ERB me-2 float-left" width="32" height="32">
                                                <use
                                                    href="{{asset('img/icons.svg#'.$certificate->certificate->project_type->image_icon)}}"></use>
                                            </svg>

                                            {{$certificate->certificate->project_type->type}}
                                        </div>
                                    </td>
                                    <td>${{number_format($certificate->rate,'2')}}</td>
                                    <td>${{price_format($certificate->amount)}}</td>

                                </tr>
                            @empty

                            @endforelse
                        </x-slot>
                    </x-data-table.table>
                </div>
            </div>
        </div>
    @else
        <div class="bg-white block-main row m-0 p-0 pt-3 table-container h-100 empty-dash">
            <div class="col-12 p-0">
                <div class="row col flex-column justify-content-center align-items-center">
                    <h6 class="fw-bold mb-2 maxw-200">Bids</h6>
                    <p class="mb-2 text-black-50 maxw-200">There are no bids
                        yet to present..</p>
                    <a class="btn button-green mx-auto" href="{{url('/buy')}}">Start bidding</a>
                </div>
            </div>
        </div>
    @endif
    @if($certificates->count() > 0)
        <div class="index-list-sm d-block d-sm-none p-0">
            <h4 class="fw-bold p-0 mb-4">Bids</h4>
            @foreach($certificates as $certificate)
                <div class="card-el p-3 mb-3">
                    <div class="card-header d-flex justify-content-between">
                        <a  class="text-decoration-none text-black">
                            <div class="d-flex align-items-center">
                                <svg class="icon {{$certificate->certificate->project_type->image}} me-2" width="32"
                                     height="32">
                                    <use href="{{asset('img/icons.svg#'.$certificate->certificate->project_type->image)}}">
                                    </use>
                                </svg>
                                <div class="d-flex flex-column">
                                    <div class="title fw-bold">{{$certificate->certificate->name}}</div>
                                    <div class="title">{{$certificate->certificate->project_type->type}}</div>
                                </div>
                            </div>
                        </a>
                        <span class="d-flex flex-column align-items-end justify-content-space-between">
                    <span class="price fw-bold"> ${{number_format($certificate->rate,'2')}}</span>
{{--                    <span class="statistic-price statistic-decrease d-flex  ms-1">--}}
                            {{--                        <svg class="icon icon-decrease me-1" width="16" height="16">--}}
                            {{--                            <use href="./img/icons.svg#icon-decrease"></use>--}}
                            {{--                        </svg> -0.38%</span>--}}
                </span>
                    </div>
                    <hr class="opacity-25">
                    <div class="card-body">
                        <p class="d-flex justify-content-between align-items-center mb-2">
                            <span class="title fw-bold text-black-50 fw-bolder">Bid Price:</span>
                            <span
                                class="result fw-bolder w-75 text-end">${{price_format($certificate->amount)}}</span>
                        </p>

                    </div>

                </div>
            @endforeach

        </div>
    @endif
</div>
