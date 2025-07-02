<div>
    @if(!$bids->isEmpty())
        <div class="bg-white block-main row m-0 p-0 table-container h-100 d-none d-sm-block">
            <div class="col-12 p-0">
                <div class="row col mb-24 m-3 p-0 ms-4">
                    <h6 class="fw-bold p-0 col-auto">Bids</h6>
                    <div class="col">
                        <select class="ms-auto" id="sort_by" title="sort_by" wire:model="sort_by">
                            <option value="certificates.name">Name</option>
                            <option value="project_types.type">Type</option>
                            <option value="bids.status">Status</option>
                        </select>
                    </div>
                    <a class="button-green ms-auto" href="{{ route('admin.bids') }}">
                        View all
                    </a>
                </div>
                <table class="classic striped-table add-height table table-striped table-borderless align-table"
                       data-height="100px">
                    <thead>
                    <tr>
                        <th data-filter-control-placeholder="Name" data-field="name">Name</th>
                        <th data-filter-control-placeholder="Type" data-field="type">Type</th>
                        <th data-filter-control-placeholder="Status" data-field="status">Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($bids as $record)
                        <tr wire:key="{{$record->id}}">
                            <td>{{$record->certificate->name }}</td>
                            <td>{{$record->certificate->project_type->type}}</td>
                            <td>
                                @if($record->status == 0)
                                    <div class="status-button pending">Pending</div>
                                @elseif($record->status == 1)
                                    <div class="status-button approved">Accepted</div>
                                @elseif($record->status == 2)
                                    <div class="status-button notapproved">Decline</div>
                                @elseif($record->status == 3)
                                    <div class="status-button offered">Offered</div>
                                @else
                                    <div class="status-button canceled">Canceled</div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white block-main row m-2 p-2 empty-dash">
            <div class="col-12 p-0">
                <div class="row col flex-column justify-content-center align-items-center">
                    <h6 class="fw-bold mb-2 maxw-200">Bids</h6>
                    <p class="mb-2 text-black-50 maxw-200">There are no bids yet to present..</p>
                    <!-- <a class="btn button-green mx-auto" href="{{url('/buy')}}">Start Bidding</a> -->
                </div>
            </div>
        </div>
    @endif
    @if(!$bids->isEmpty())
        <div class="index-list-sm d-block d-sm-none p-0">
            <div class="d-flex mb-4 ">
                <h6 class="fw-bold p-0 col-auto">Bids</h6>

                <select class="ms-auto" id="sort_by" title="sort_by" wire:model="sort_by">
                    <option value="certificates.name">Name</option>
                    <option value="project_types.type">Type</option>
                    <option value="bids.status">Status</option>
                </select>

                <a class="button-green ms-auto" href="{{ route('admin.bids') }}">
                    View all
                </a>
            </div>
            @foreach($bids as $record)
                <div class="card-el p-3 mb-3">
                    <div class="card-header d-flex justify-content-between">
                        <a  class="text-decoration-none text-black">
                            <div class="d-flex align-items-center">
                                <svg class="icon {{$record->certificate->project_type->image_icon}} me-2" width="32"
                                     height="32">
                                    <use href="../img/icons.svg#{{$record->certificate->project_type->image_icon}}">
                                    </use>
                                </svg>
                                <div class="d-flex flex-column">
                                    <div class="title fw-bold">{{$record->certificate->name}}</div>
                                    <div class="title">{{$record->certificate->project_type->type}}</div>
                                </div>
                            </div>
                        </a>
                        <span class="d-flex flex-column align-items-end justify-content-space-between">
                    <span class="price fw-bold"> ${{number_format($record->rate,'2')}}</span>
{{--                    <span class="statistic-price statistic-decrease d-flex  ms-1">--}}
                            {{--                        <svg class="icon icon-decrease me-1" width="16" height="16">--}}
                            {{--                            <use href="./img/icons.svg#icon-decrease"></use>--}}
                            {{--                        </svg> -0.38%</span>--}}
                </span>
                    </div>
                    <hr class="opacity-25">
                    <div class="card-body">
                        <p class="d-flex justify-content-between align-items-center mb-2">
                            <span class="title fw-bold text-black-50 fw-bolder">Status:</span>
                            @if($record->status == 0)
                                <span class="status-button pending">Pending</span>
                            @elseif($record->status == 1)
                                <span class="status-button approved">Accepted</span>
                            @elseif($record->status == 2)
                                <span class="status-button notapproved">Decline</span>
                            @elseif($record->status == 3)
                                <span class="status-button offered">Offered</span>
                            @else
                                <span class="status-button canceled">Canceled</span>
                            @endif
                        </p>

                    </div>

                </div>
            @endforeach

        </div>
    @endif
</div>
