<div>
    @if(!$certificates->isEmpty())
        <div class="bg-white block-main row m-2 p-0 table-container d-none d-sm-block">
            <div class="col-12 p-0">
                <div class="row col mb-24 m-3 p-0 ms-4">
                    <h6 class="fw-bold p-0 col-auto">Carbon Credits</h6>
                    <a class="button-green ms-auto" href="{{ route('admin.certificates') }}">
                        View all
                    </a>
                </div>
                <table class="classic striped-table add-height table table-striped table-borderless align-table"
                       data-filter-control="true" data-height="450" data-search="true"
                       data-classes='table table-striped table-borderless default-search'>
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Country</th>
                        <th>Type</th>
                        <th>Holding User</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($certificates as $record)
                        <tr wire:key="{{$record->id}}">
                            <td>{{$record->name}}</td>
                            <td>{{$record->country?$record->country->name:'N/A'}}</td>
                            <td>{{$record->project_type->type}}</td>
                            <td>{{$record->user->name}}</td>
                            <td>
                                @if($record->status == 1)
                                    <div class="status-button pending">
                                        Pending
                                    </div>
                                @elseif($record->status == 2)
                                    <div class="status-button approved">
                                        Approved
                                    </div>
                                @elseif($record->status == 3)
                                    <div class="status-button onsell">
                                        On Sell
                                    </div>
                                @else
                                    <div class="status-button notapproved">
                                        Declined
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white block-main row m-0 p-0 pt-3 h-50 empty-dash">
            <div class="col-12 p-0">
                <div class="row col flex-column justify-content-center align-items-center">
                    <h6 class="fw-bold mb-2 maxw-200">Carbon Credits</h6>
                    <p class="mb-2 text-black-50 maxw-200">There are no carbon credits...</p>
                    <!-- <a class="btn button-green mx-auto" href="{{url('/buy')}}">Browse certificates for purchase</a> -->
                </div>
            </div>
        </div>
    @endif
    @if(!$certificates->isEmpty())
            <div class="index-list-sm d-block d-sm-none p-0">
                <div class="d-flex mb-4 ">
                    <h6 class="fw-bold p-0 col-auto">Carbon Credits</h6>
                    <a class="button-green ms-auto" href="{{ route('admin.certificates') }}">
                        View all
                    </a>
                </div>
                @foreach ($certificates as $record)
                    <div class="card-el p-3 mb-3">
                        <div class="card-header d-flex justify-content-between">
                            <a class="text-decoration-none text-black">
                                <div class="d-flex align-items-center">
                                    <svg class="icon {{$record->project_type->image_icon}} me-2" width="32"
                                         height="32">
                                        <use href="../img/icons.svg#{{$record->project_type->image_icon}}">
                                        </use>
                                    </svg>
                                    <div class="d-flex flex-column">
                                        <div class="title fw-bold">{{$record->name}}</div>
                                        <div class="title">{{$record->project_type->type}}</div>
                                    </div>
                                </div>
                            </a>

                        </div>
                        <hr class="opacity-25">
                        <div class="card-body">
                            <p class="d-flex justify-content-between align-items-center mb-2">
                                <span class="title fw-bold text-black-50 fw-bolder">Country:</span>
                                <span class="result fw-bolder">{{ $record->country?$record->country->name :'N/A'}}</span>
                            </p>
                            <p class="d-flex justify-content-between align-items-center mb-2">
                                <span class="title fw-bold text-black-50 fw-bolder">Holding User:</span>
                                <span class="result fw-bolder">{{ $record->user->name }}</span>
                            </p>
                            <p class="d-flex justify-content-between align-items-center mb-2">
                                <span class="title fw-bold text-black-50 fw-bolder">Status:</span>
                            @if($record->status == 1)
                                <span class="status-button pending">
                                    Pending
                                </span>
                            @elseif($record->status == 2)
                                <span class="status-button approved">
                                    Approved
                                </span>
                            @elseif($record->status == 3)
                                <span class="status-button onsell">
                                    On Sell
                                </span>
                            @else
                                <span class="status-button notapproved">
                                    Declined
                                </span>
                                @endif
                            </p>

                        </div>

                    </div>
                @endforeach

            </div>
    @endif
</div>
