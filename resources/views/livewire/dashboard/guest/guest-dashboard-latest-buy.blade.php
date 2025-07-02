<div class="bg-white block-main row m-0 p-0 pt-5  table-contkainer h-100">
    <div class="col-12 p-0 row m-0">
        <div wire:ignore class="m-0 p-0">
            <x-data-table.table :model="$certificates" :columns="[]" :wantSearching="false" :dateFilter="false">
                <x-slot name="title">
                    {{ __('Trending') }}
                </x-slot>
                <x-slot name="head">
                    <tr>
                        <th data-filter-control-placeholder="Type" data-field="Type" data-width="20"
                            data-width-unit="%">
                            {{ __('Type') }}
                        </th>
                        <th data-filter-control-placeholder="Name" data-field="Name">
                            {{ __('Name') }}
                        </th>
                        <th data-filter-control-placeholder="Country" data-field="Country">
                            {{ __('Country') }}
                        </th>
                        <th>{{ __('Quantity') }}</th>
                        <th x-data="{}" data-filter-control-placeholder="Current value" data-field="Currentlue">
                            <select class="selectpicker-class default" data-container="body"
                                    x-model="$wire.currentValue" x-on:change="$wire.setCurrentValue($event.target.value)">
                                <option value="1d">{{ __('Current value 1D') }}</option>
                                <option value="7d">{{ __('Current value 7D') }}</option>
                                <option value="1m">{{ __('Current value 1M') }}</option>
                                <option value="6m">{{ __('Current value 6M') }}</option>
                            </select>
                        </th>
                        <th data-filter-control-placeholder="Buttons" data-field="Buttons"
                            data-width="203px">
                        </th>
                    </tr>
                </x-slot>
                <x-slot name="body">
                    @forelse ($certificates as $certificate)
                        <tr>
                            <td>
                                <a href="#" x-on:click="$wire.emit('openCloseRestrictionModal')"
                                   class="text-decoration-none text-black">
                                    <svg class="icon icon-Forest-ERB me-2" width="32" height="32">
                                        <use
                                            href="{{asset('img/icons.svg#'.$certificate->project_type->image_icon)}}"></use>
                                    </svg>
                                    {{ $certificate->project_type->type??'' }}
                                </a>
                            </td>
                            <td>{{ $certificate->name }}</td>
                            <td>{{ $certificate->country->name }}</td>
                            <td>{{ $certificate->sell_certificate->remaining_units }}</td>
                            <td>
                                <div class="d-flex">
                                <span class="d-flex flex-column align-items-end justify-content-space-between">
                                    <span
                                        class="price fw-bold">
                                        ${{ price_format( $certificate->sell_certificate->total_amount / $certificate->sell_certificate->units) }}
                                    </span>
                                    <span class="statistic-price statistic-decrease d-flex  ms-1">
                                        @if($certificate->price_average <= 0)
                                            <svg class="icon icon-decrease me-1" width="16" height="16">
                                                <use href="{{asset('img/icons.svg#icon-decrease')}}"></use>
                                            </svg> {{$certificate->price_average}}%
                                        @else
                                            <svg class="icon icon-increase me-1" width="16" height="16">
                                                <use href="{{asset('img/icons.svg#icon-increase')}}"></use>
                                            </svg> {{$certificate->price_average}}%
                                        @endif
                                    </span>
                                </span>
                                    <span class="small-graph ms-2">
                                    <canvas class="small-graph"></canvas>
                                </span>
                                </div>
                            </td>
                            <td x-data="{}">
                                <a href="#"
                                   x-on:click="$wire.emit('openCloseRestrictionModal')"
                                   class="button-green buy-table"
                                   type="button">
                                    {{ __('Buy') }}
                                </a>
                            </td>
                        </tr>
                    @empty

                    @endforelse
                </x-slot>
            </x-data-table.table>
        </div>
    </div>
</div>
