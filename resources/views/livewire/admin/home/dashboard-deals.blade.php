<div>
    @if(!$deals->isEmpty())
        <div class="bg-white block-main row m-0 p-0 table-container h-100 d-none d-sm-block">
            <div class="col-12 p-0">
                <div class="row col mb-24 m-3 p-0 ms-4">
                    <h6 class="fw-bold p-0 col-auto">Deals</h6>
                    <div class="col">
                        <select class="ms-auto" id="sort_by" title="sort_by" wire:model="sort_by">
                            <option value="certificates.name">Name</option>
                            <option value="project_types.type">Type</option>
                            <option value="users.name">Buyer</option>
                        </select>
                    </div>
                    <a class="button-green ms-auto" href="{{ route('admin.deals') }}">
                        View all
                    </a>
                </div>
                <table class="classic striped-table add-height table table-striped table-borderless align-table"
                       data-height="100px">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Buyer</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($deals as $record)
                        <tr wire:key="{{$record->id}}">
                            <td>{{$record->certificate->name}}</td>
                            <td>{{ $record->certificate->project_type->type }}</td>
                            <td>{{ $record->buyer->name }}</td>
                            <td>{{Carbon\Carbon::parse($record->created_at)->format('d/m/Y')}}</td>
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
                    <h6 class="fw-bold mb-2 maxw-200">Deals</h6>
                    <p class="mb-2 text-black-50 maxw-200">There are no more Deals...</p>
                    <!-- <a class="btn button-green mx-auto" href="{{url('/buy')}}">Browse deals</a> -->
                </div>
            </div>
        </div>
    @endif
    @if(!$deals->isEmpty())
        <div class="index-list-sm d-block d-sm-none p-0">
            <div class="d-flex mb-4 ">
                <h6 class="fw-bold p-0 col-auto">Deals</h6>

                <select class="ms-auto" id="sort_by" title="sort_by" wire:model="sort_by">
                    <option value="certificates.name">Name</option>
                    <option value="project_types.type">Type</option>
                    <option value="users.name">Buyer</option>
                </select>

                <a class="button-green ms-auto" href="{{ route('admin.deals') }}">
                    View all
                </a>
            </div>
            @foreach ($deals as $record)
                <div class="card-el p-3 mb-3">
                    <div class="card-header d-flex justify-content-between">
                        <a class="text-decoration-none text-black">
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

                    </div>
                    <hr class="opacity-25">
                    <div class="card-body">
                        <p class="d-flex justify-content-between align-items-center mb-2">
                            <span class="title fw-bold text-black-50 fw-bolder">Buyer:</span>
                            <span class="result fw-bolder">{{ $record->buyer->name }}</span>
                        </p>
                         <p class="d-flex justify-content-between align-items-center mb-2">
                            <span class="title fw-bold text-black-50 fw-bolder">Date:</span>
                            <span class="result fw-bolder">{{Carbon\Carbon::parse($record->created_at)->format('d/m/Y')}}</span>
                        </p>

                    </div>

                </div>
            @endforeach

        </div>
    @endif
</div>
