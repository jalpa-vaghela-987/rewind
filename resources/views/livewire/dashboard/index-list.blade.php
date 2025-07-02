<div>
@if($certificates->count() > 0)
<div class="bg-white block-main row m-0 p-0 pt-3 table-container h-100 d-none d-sm-flex">
    <div class="col-12 p-0">
        <div class="row col mb-24 ms-4">
            <h6 class="fw-bold p-0">Index List</h6>
        </div>
        <div class="half-table-container mh-100">
            <x-data-table.table :model="$certificates" :columns="[]" :wantSearching="true" :dateFilter="true">
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
                        <th data-filter-control-placeholder="Ask Price" data-field="price_per_unit" class="w-10">
                            {{ __('Ask Price') }}
                        </th>
                        <th data-filter-control-placeholder="Buttons" data-field="Buttons"
                        class="w-20">
                        </th>
                    </tr>
                </x-slot>
                <x-slot name="body">
                    @forelse ($certificates as $key => $certificate)
                        <tr class="even:bg-white odd:bg-gray-50" wire:key="{{$key}}">
                            <td x-data="{}">
                                <a href="{{route('buy.show.certificate',['id' => $certificate->id])}}"
                                    class="text-decoration-none text-black">
                                    <div class="d-flex align-items-center">
                                    <svg class="icon icon-Forest-ERB me-2" width="32" height="32">
                                        <use
                                            href="{{asset('img/icons.svg#'.$certificate->certificate->project_type->image_icon)}}"></use>
                                    </svg>
                                    {{ $certificate->certificate->project_type->type??'' }}
                                    </div>
                                </a>
                            </td>
                            <td>{{$certificate->certificate->name}}</td>
                            <td>{{$certificate->certificate->country->name}}</td>
                            <td>{{$certificate->remaining_units }}</td>
                            <td>
                                <div class="d-flex">
                                    <span class="d-flex flex-column align-items-end justify-content-space-between">
                                        <span
                                            class="price fw-bold">
                                            ${{price_format($certificate->price_per_unit) }}
                                        </span>
                                    </span>
                                </div>
                            </td>
                            <td x-data="{}">
                                <div class="flex">
                                    <a href="javascript:void(0);"
                                        wire:click="openModal({{ $certificate->id }})"
                                        class="button-green buy-table me-2 buy-certitficate"
                                        type="button"
                                        :disabled="{{$disable_id==$certificate->id?'true':'false'}}"
                                    >
                                        {{ __('Buy') }}
                                    </a>
                                    <a href="{{route('buy.show.certificate', $certificate->id)}}"
                                        class="button-secondary buy-table me-2"
                                        type="button">
                                        {{ __('View') }}
                                    </a>
                                    <div class="dropdown button-grey dropdown-grey col-auto">
                                        <button class="btn dropdown-toggle bs-placeholder btn-light show"
                                            type="button" id="genderDropdown" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="genderDropdown">
                                            <a class="dropdown-item cursor-pointer" wire:click="openPriceAlertModal('{{$certificate->id}}')">{{ __('Set Price Alert') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                    @endforelse
                </x-slot>
            </x-data-table.table>
        </div>
    </div>
    @push('modals')
        @livewire('buy.buy-bid-modal')
        @livewire('buy.price-alert-modal')
        @livewire("profile.detail.user.resend-verification-s-m-s-modal",['user'=>$user])
    @endpush
</div>
@else
    <div class="bg-white block-main row m-0 p-0 pt-3  table-container h-100 empty-dash">
        <div class="col-12 p-0">
            <div class="row col flex-column justify-content-center align-items-center">
                <h6 class="fw-bold mb-2 maxw-210">Index List</h6>
                <p class="mb-2 text-black-50 maxw-200">There are no certificate
                    yet to present..</p>
                <a class="btn button-green mx-auto" href="{{url('/sell')}}">Add Certificate</a>
            </div>
        </div>
    </div>
@endif
    @if($certificates->count() > 0)
        <div class="index-list-sm d-block d-sm-none p-0">
            <h4 class="fw-bold p-0 mb-4">Index List</h4>
            @foreach($certificates as $certificate)
                <div class="card-el p-3 mb-3">
                    <div class="card-header d-flex justify-content-between">
                        <a href="{{route('buy.show.certificate',['id' => $certificate->id])}}"
                           class="text-decoration-none text-black">
                            <div class="d-flex align-items-center">
                                <svg class="icon icon-Forest-ERB me-2" width="32" height="32">
                                    <use
                                        href="{{asset('img/icons.svg#'.$certificate->certificate->project_type->image_icon)}}"></use>
                                </svg>
                                {{ $certificate->certificate->project_type->type??'' }}
                            </div>
                        </a>
                        <span class="d-flex flex-column align-items-end justify-content-space-between">
                    <span class="price fw-bold">  ${{price_format($certificate->price_per_unit) }}</span>
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
                            <span
                                class="result fw-bolder w-75 text-end">{{$certificate->certificate->country->name}}</span>
                        </p>
                        <p class="d-flex justify-content-between align-items-center mb-2">
                            <span class="title fw-bold text-black-50 fw-bolder">Quantity:</span>
                            <span
                                class="result fw-bolder w-75 text-end">{{$certificate->remaining_units }}</span>
                        </p>

                    </div>

                </div>
            @endforeach

        </div>
    @endif
</div>
