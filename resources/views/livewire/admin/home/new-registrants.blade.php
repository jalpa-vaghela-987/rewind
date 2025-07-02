<div>
    @if(!$new_registants->isEmpty())
        <div class="bg-white block-main row m-0 p-0 pt-3 table-container h-50  d-none d-sm-block">
            <div class="col-12 p-0">
                <div class="row col mb-24 ms-4">
                    <h6 class="fw-bold p-0">Approve New Registrants</h6>
                </div>
                <table class="classic striped-table add-height table table-striped table-borderless align-table">
                    <thead>
                    <tr>
                        <th data-filter-control-placeholder="Name" data-field="name">Name</th>
                        <th data-filter-control-placeholder="Country" data-field="country">Country</th>
                        <th data-filter-control-placeholder=" Email" data-field="email"> Email</th>
                        <th data-filter-control-placeholder="Status" data-field="status">Status</th>
                        <th>Review for approval</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($new_registants as $record)
                        <tr wire:key="{{$record->id}}">
                            <td>{{$record->name}}</td>
                            <td>{{$record->country?$record->country->name:'N/A'}}</td>
                            <td>{{$record->email}}</td>
                            <td>
                                <div class="status-button pending">
                                    Pending
                                </div>
                            </td>
                            <td>
                                <a wire:click.prevent="$emit('showPreviewModal','{{base64_encode($record->id)}}')"
                                   class="button-green"
                                   type="button"
                                   wire:loading.attr="disabled">{{ __('Preview') }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white block-main row m-0 p-0 pt-3 table-container h-50">
            <div class="col-12 p-0">
                <div class="row col flex-column justify-content-center align-items-center">
                    <h6 class="fw-bold mb-2 maxw-210">New Registrants</h6>
                    <p class="mb-2 text-black-50 maxw-200 text-center">There are no new registrants to present..</p>
                    <!-- <a class="btn button-green mx-auto" href="{{url('/buy')}}">Browse certificates for purchase</a> -->
                </div>
            </div>
        </div>
    @endif
    @if(!$new_registants->isEmpty())
        <div class="index-list-sm d-block d-sm-none p-0">
            <h4 class="fw-bold p-0 mb-4">Approve New Registrants</h4>
            @foreach ($new_registants as $record)
                <div class="card-el p-3 mb-3" wire:key="{{$record->id}}">
                    <div class="card-header d-flex justify-content-between gap-1">
                        <a class="text-decoration-none text-black w-50">
                            <div class="d-flex flex-column w-100 align-self-strech justify-content-between">
                                <div class="title fw-bold">{{$record->name}}</div>
                                <div class="title">{{$record->country?$record->country->name:'N/A'}}</div>
                            </div>
                        </a>
                        <span class="d-flex flex-column align-items-start justify-content-center">

                                        <span class="status-button pending cursor-pointer">
                                 Pending
                        </span>

                </span>
                    </div>
                    <hr class="opacity-25">
                    <div class="card-body">
                        <p class="d-flex justify-content-between align-items-center mb-2">
                            <span class="title fw-bold text-black-50 fw-bolder">Email:</span>
                            <span class="result fw-bolder w-75 text-end">{{$record->email}}</span>
                        </p>
                    </div>
                    <hr class="opacity-25">
                    <div class="d-flex justify-content-end">
                        <div class="flex justify-content-around">
                            <a wire:click.prevent="$emit('showPreviewModal','{{base64_encode($record->id)}}')"
                               class="button-green"
                               type="button"
                               wire:loading.attr="disabled">{{ __('Preview') }}
                            </a>
                        </div>
                    </div>


                </div>
            @endforeach

        </div>
    @endif
</div>
