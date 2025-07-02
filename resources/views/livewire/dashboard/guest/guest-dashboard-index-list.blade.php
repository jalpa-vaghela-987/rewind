<div class="col p-0 border-left-before pt-5 pb-32 " wire:ignore>
    <div class="row col mb-24 ms-4">
        <h6 class="fw-bold p-0">Index List</h6>
    </div>
    <table class="classic striped-table add-height table table-striped table-borderless table-fixed align-table">
        <thead>
        <tr>
            <th data-filter-control-placeholder="Name" data-field="Name">Name</th>
            <th data-filter-control-placeholder="Value" data-field="Value">Value</th>
            <th data-filter-control-placeholder="Change" data-field="Change">Change (%)</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($certificates as $certificate)
            <tr class="even:bg-white odd:bg-gray-50">
                <td x-data="{}">
                        <svg class="icon icon-Forest-ERB me-2 float-left" width="32" height="32">
                            <use href="{{asset('img/icons.svg#'.$certificate[0]->certificate->project_type->image_icon)}}"></use>
                        </svg>{{$certificate[0]->certificate->project_type->type}}</td>
                <td>${{ price_format($certificate[0]->price) }}</td>
                <td><span class="statistic-price statistic-decrease d-flex  ms-1">
                <svg class="icon icon-decrease me-1" width="16" height="16">
                   @if($certificate[0]->differenceType == 'inc')
                        <use href="{{asset('/img/icons.svg#icon-increase')}}"></use>
                    @else
                        <use href="{{asset('/img/icons.svg#icon-decrease')}}"></use>
                    @endif
                </svg> @if($certificate[0]->differenceType == 'inc') + @else - @endif{{price_format($certificate[0]->priceDifference)}}%</span></td>
            </tr>
        @empty
        @endforelse
        </tbody>
    </table>
</div>

