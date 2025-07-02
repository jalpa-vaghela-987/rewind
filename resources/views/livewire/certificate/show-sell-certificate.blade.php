<div>
    <div class="row col-12 p-0 mb-20 showCertificateTopBlock">
        <div class="bg-white block-main row  p-0 h-100 pb-32 flex-column">
            <div class="col-12 p-32 row m-0">
                <nav aria-label="breadcrumb" class="mb-40">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{url('/sell')}}">Sell</a></li>
                        <li class="breadcrumb-item active"
                            aria-current="page">{{$sellCertificate->certificate->name}}</li>
                    </ol>
                    @if($sellCertificate->status == 1)
                        <div class="ml-2 status-button pending">
                            Pending approval
                        </div>
                    @elseif($sellCertificate->status == 2)
                        <div class="ml-2 status-button approved">
                            Approved
                        </div>
                    @elseif($sellCertificate->status == 3)
                        <div class="ml-2 status-button onsell">
                            On Sell
                        </div>
                    @else
                        <div class="ml-2 status-button notapproved">
                            Declined
                        </div>
                    @endif
                </nav>
                <div class="col-4 d-flex align-items-center">
                    <svg width="48" height="48" class="icon icon-Forest-ERB">
                        <use href="{{asset('img/icons.svg#'.$sellCertificate->certificate->project_type->image_icon)}}"></use>
                    </svg>
                    <div class="info-main d-flex flex-column">
                        <span class="fw-bold">{{$sellCertificate->certificate->project_type->type}}</span>
                        <span class="main-info d-flex align-items-baseline">
                            <span class="price">${{ $sellCertificate->total_price }}</span>
{{--                            <span class="statistic-price statistic-decrease d-flex  ms-1">--}}
{{--                                <svg class="icon icon-decrease me-1" width="16" height="16">--}}
{{--                                    @if($differenceType == 'inc')--}}
{{--                                        <use href="{{asset('/img/icons.svg#icon-increase')}}"></use>--}}
{{--                                    @else--}}
{{--                                        <use href="{{asset('/img/icons.svg#icon-decrease')}}"></use>--}}
{{--                                    @endif--}}
{{--                                </svg>--}}
{{--                                --}}{{--@if($differenceType == 'inc')--}}
{{--                                    +--}}
{{--                                @else--}}
{{--                                    ---}}
{{--                                @endif--}}
{{--                                {{price_format($priceDifference)}}%--}}
{{--                            </span>--}}
                        </span>
                    </div>
                </div>
                <div class="edit-info-sell ms-0 mt-3 mt-md-0 align-self-end fs-16 col-4">
                    <span>
                        Unit Price:<b>${{ price_format($sellCertificate->price_per_unit) }}</b>
                    </span>
                    <a href="javascript:void(0)" class="grey-icon-edit custom-modal-show ms-2 top-3"
                       wire:click="unitModal({{$sellCertificate->price_per_unit}})">
                        <svg class="icon icon-Pencil" width="16" height="16">
                            <use href="{{asset('img/icons.svg#icon-Pencil')}}"></use>
                        </svg>
                    </a>
                </div>
                <div class="edit-info-sell ms-0  align-self-end fs-16 col-4">
                    <span>
                        Quantity:<b>{{$sellCertificate->remaining_units}}</b>
                    </span>
                    <a href="javascript:void(0)"
                       class="grey-icon-edit custom-modal-show ms-2 top-3"
                       wire:click="quantityModal({{$sellCertificate->remaining_units}})">
                        <svg class="icon icon-Pencil" width="16" height="16">
                            @if(($sellCertificate->status == 1 && $sellCertificate->is_main) || ($sellCertificate->status === 3))
                                <use href="{{asset('img/icons.svg#icon-Pencil')}}"></use>
                            @endif
                        </svg>
                    </a>
                </div>
                <div class="edit-info-sell ms-0 align-self-end fs-16 col-4">
                    <span>
                        Country:<b>{{$sellCertificate->certificate->country->name}}</b>
                    </span>
                </div>
                <div class="edit-info-sell ms-0 align-self-end fs-16 col-4">
                    <span>
                        Project Year:<b>{{$sellCertificate->certificate->project_year}}</b>
                    </span>
                </div>
                @if($sellCertificate->status == 3)
                    <div class="col-4 col-xl-2 d-flex">
                        <a href="javascript:void(0)"
                           class="button-secondary w-100"
                           wire:click="openCancelSellCertificateModal({{$sellCertificate->id}})"
                           type="button">
                            Cancel Sell
                        </a>
                    </div>
                @endif
                @if($sellCertificate->status == 2)
                    <div class="col-4 col-xl-2 d-flex">
                        <a href="javascript:void(0)"
                           class="button-green w-100"
                           wire:click="openModal({{$sellCertificate->id}})"
                           type="button">
                            Sell Carbon Credit
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
        {{--                            <a class="nav-link"--}}
        {{--                               id="soon-tab"--}}
        {{--                               disabled="disabled"--}}
        {{--                               data-bs-toggle="tab"--}}
        {{--                               data-bs-target="#soon"--}}
        {{--                               type="button"--}}
        {{--                               role="tab"--}}
        {{--                               aria-controls="soon"--}}
        {{--                               aria-selected="false">--}}
        {{--                                Coming soon--}}
        {{--                            </a>--}}
        {{--                        </li>--}}
        {{--                    </ul>--}}
        {{--                    <div class="tab-content mb-24" id="overviewTabsContent">--}}
        {{--                        <div class="tab-pane fade show active row" id="overview" role="tabpanel"--}}
        {{--                             aria-labelledby="overview-tab">--}}
        {{--                            <div class="col-12 row align-items-center  d-flex m-0 justify-content-center">--}}
        {{--                                <div class="ms-auto col-auto mt-90 mb-20">--}}
        {{--                                    <div class="btn-group btn-row" role="group" aria-label="Basic example">--}}
        {{--                                        <button type="button"--}}
        {{--                                                wire:click="$emit('certificate-selected',['{{ $sellCertificate->certificate_id }}','14'])"--}}
        {{--                                                class="btn fiterBtn @if($maxDays == 14) active @endif "--}}
        {{--                                                data-days="14">--}}
        {{--                                            2 Weeks--}}
        {{--                                        </button>--}}
        {{--                                        <button type="button"--}}
        {{--                                                wire:click="$emit('certificate-selected',['{{ $sellCertificate->certificate_id }}','30'])"--}}
        {{--                                                class="btn fiterBtn @if($maxDays == 30) active @endif"--}}
        {{--                                                data-days="30">--}}
        {{--                                            1 Month--}}
        {{--                                        </button>--}}
        {{--                                        <button type="button"--}}
        {{--                                                wire:click="$emit('certificate-selected',['{{ $sellCertificate->certificate_id }}','180'])"--}}
        {{--                                                class="btn fiterBtn @if($maxDays == 180) active @endif"--}}
        {{--                                                data-days="180">--}}
        {{--                                            6 Month--}}
        {{--                                        </button>--}}
        {{--                                        <button type="button"--}}
        {{--                                                wire:click="$emit('certificate-selected',['{{ $sellCertificate->certificate_id }}','365'])"--}}
        {{--                                                class="btn fiterBtn @if($maxDays == 365) active @endif"--}}
        {{--                                                data-days="365">--}}
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
        <div class="col gap-5 d-flex flex-column p-0">
            <div class="bg-white block-main row m-0  h-100">
                <div class="m-0 p-0">
                    <div class="d-flex justify-content-between">
                        <label class="align-self-end fs-24 ">Carbon Credit Photo Gallery</label>
                        @if(!blank($currentImage))
                            <div class="btn-group">
                                <button class="button-green btn-xs"
                                        wire:click="prevImage" {{ $currentImageIndex == 0 ? 'disabled' : '' }}>
                                    <i class="fa fa-chevron-left" aria-hidden="true"></i>
                                </button>

                                <button class="button-green"
                                        wire:click="nextImage" {{ $currentImageIndex == ($total_files - 1) ? 'disabled' : '' }}>
                                    <i class="fa fa-chevron-right" aria-hidden="true"></i>
                                </button>
                            </div>
                        @endif

                    </div>

                    @if(!blank($currentImage))
                        <div class="gallery p-0 mt-2">
                            @if (!$errors->has('file_path'))
                                @if($total_files > 0)
                                    <img src="{{ $currentImage }}" alt="buy-certificate-image" class="certificate_image" class="certificate_image">
                                @else
                                    <img
                                        src="https://picsum.photos/id/19/960/960"
                                        alt="buy-certificate-image">
                                @endif
                            @endif
                        </div>
                    @endif
                </div>
                <div class="m-0 p-0 mt-4">
                    <div class="mb-30  fs-16 buy-desc">
                        <label class="align-self-end fs-24">Carbon Credit Description</label>
                        <p class="text-justify">{{$sellCertificate->certificate->description}} </p>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-12 col-lg-5 d-flex flex-column p-0 h-100">
            <div class="bg-white block-main row m-0  h-100">
                <div class="m-0 p-0">
                    <div class="row mb-30  fs-16 buy-desc">
                        <label class="align-self-end fs-24">Carbon Credit Location</label>
                        <p class="text-justify mt-1"><b>Latitude:</b>{{$sellCertificate->certificate->lattitude}}, <b>Longitude:</b>{{$sellCertificate->certificate->longitude}}
                        </p>
                    </div>
                    <div class="row mb-30  fs-16 buy-desc">
                        <label class="align-self-end fs-24 mb-2">Carbon Credit Details</label>
                        <div class="col-4 d-flex flex-col mb-2">
                              <label class="fs-20"><b>Vintage</b></label>
                              <label>{{$sellCertificate->certificate->vintage}}</label>
                        </div>
                        <div class="col-4 d-flex flex-col mb-2">
                              <label class="fs-20"><b>Verified By</b></label>
                              <label>{{$sellCertificate->certificate->verify_by}}</label>
                        </div>
                        <div class="col-4 d-flex flex-col mb-2">
                              <label class="fs-20"><b>Acres</b></label>
                              <label>{{$sellCertificate->certificate->total_size}}</label>
                        </div>
                        <div class="col-4 d-flex flex-col">
                              <label class="fs-20"><b>Registry Id</b></label>
                              <label>{{$sellCertificate->certificate->registry_id}}</label>
                        </div>
                        <div class="col-8 d-flex flex-col">
                              <label class="fs-20"><b>Carbon Credit</b></label>
                              <a href="{{$sellCertificate->certificate->link_to_certificate}}" class="text-decoration-none" target="_blank">View</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <x-jet-modal class="modal fade" wire:model="showSellCertificateModal">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content p-32">
                <div class="modal-body p-0 row">
                    <div class="row col-12 p-0 m-0">
                        <h5 class="black-color col fw-bold mb-20">
                            <svg class="icon icon-Forest-ERB me-2" width="32" height="32">
                                <use
                                    href="{{asset('img/icons.svg#'.$sellCertificate->certificate->project_type->image_icon)}}"></use>
                            </svg>
                            Sell {{ $sellCertificate->certificate->project_type->type }}
                        </h5>
                        <button type="button"
                                class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2"
                                wire:click="closeModal"></button>
                        <div class="col-12 d-flex align-items-baseline mb-20">
                            <h5 class="price fw-bold me-3">${{price_format($amount)}}</h5>
{{--                            <span class="statistic-price statistic-increase d-flex align-items-center fs-12">--}}
{{--                                <svg class="icon icon-triangle-top me-1" width="8" height="8">--}}
{{--                                    <use href="./img/icons.svg#icon-triangle-top"></use>--}}
{{--                                </svg>{{number_format($valueDiff,2)}} (--}}
{{--                                --}}{{--@if($differenceType == 'inc') + @else - @endif--}}{{-- {{number_format($priceDifference,2)}})--}}
{{--                            </span>--}}
                        </div>

                    </div>
                    <div class="row col-12 mx-0 black-10 mb-24">
                        <hr class="m-0">
                    </div>
                    <form class="row col-12 p-0 m-0">
                        <div class="col-12 mb-24 row d-flex align-items-center">
                            <div class="col-3"><label for="pricePerUnit" class="fs-16">Price Per Unit</label></div>
                            <div class="col-9 d-flex">
                                <span class="input-group-prepend col-auto">
                                    <button type="button" class="btn button-green btn-number1"
                                            wire:click="decrease('pricePerUnit')" data-type="minus"
                                            data-field="pricePerUnit"><svg class="icon icon-Minus-Icon" width="24"
                                                                           height="24">
                                            <use href="{{asset('img/icons.svg#icon-Minus-Icon')}}"></use>
                                        </svg>
                                    </button>
                                </span>
                                <input type="number" wire:model="pricePerUnit"
                                       class="form-control default mx-2 w-50 text-center fw-bold"
                                       name="pricePerUnit"
                                       id="pricePerUnit">
                                <span class="input-group-append col-auto">
                                    <button type="button" class="btn button-green btn-number1"
                                            data-type="plus" wire:click="increase('pricePerUnit')"
                                            data-field="pricePerUnit">
                                        <svg class="icon icon-Plus-Icon" width="24" height="24">
                                            <use href="{{asset('img/icons.svg#icon-Plus-Icon')}}"></use>
                                        </svg>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="col-12 mb-24 row d-flex align-items-center">
                            <div class="col-3"><label for="QTY" class="fs-16">QTY</label></div>
                            <div class="col-9 d-flex">
                                <span class="input-group-prepend col-auto">
                                    <button type="button" class="btn button-green btn-number1"
                                            wire:click="decreaseAmount" data-type="minus"
                                            data-field="QTY"><svg class="icon icon-Minus-Icon" width="24"
                                                                  height="24">
                                            <use href="{{asset('img/icons.svg#icon-Minus-Icon')}}"></use>
                                        </svg>
                                    </button>
                                </span>
                                <input type="text" wire:model="unit" readonly
                                       class="form-control default mx-2 w-50 text-center fw-bold" name="QTY" id="QTY"
                                       min="0" max="100" value="75">
                                <span class="input-group-append col-auto">
                                    <button type="button" class="btn button-green btn-number1"
                                            data-type="plus" wire:click="increaseAmount" data-field="QTY">
                                        <svg class="icon icon-Plus-Icon" width="24" height="24">
                                            <use href="{{asset('img/icons.svg#icon-Plus-Icon')}}"></use>
                                        </svg>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="col-12 mb-24">
                            <hr class="m-0">
                        </div>

                        <div class="col-12 d-flex justify-content-between mb-20">
                            <div class="col-auto fs-16 d-flex justify-content-center align-items-center">Total:</div>
                            <div class="col-auto fs-16 d-flex justify-content-center align-items-center">
                                USD <span class="ms-2 fw-bold fs-24">${{price_format($total)}}</span>
                            </div>
                        </div>
                        @if(!$bank)
                            <div class="col-12 mb-24">
                                <hr class="m-0">
                            </div>
                            <div class="col-12 d-flex justify-content-between mb-20">
                                <div class="col-auto fs-16 d-flex justify-content-center align-items-center">
                                    Your Bank Details is missing
                                </div>
                                <div class="col-auto fs-16 d-flex justify-content-center align-items-center">
                                    <a class="button-green w-100"
                                       wire:click="$emit('openCloseBankFormModal')"
                                       href="javascript:void(0)">
                                        Add Bank Account
                                    </a>
                                </div>
                            </div>
                            <div class="row col-12 m-0">
                                <a class="button-green w-100" wire:click="sellCertificate"
                                   href="javascript:void(0)" disabled>
                                    Sell
                                </a>
                            </div>
                        @else
                            <div class="row col-12 m-0">
                                <a class="button-green w-100" wire:click="sellCertificate"
                                   href="javascript:void(0)">
                                    Sell
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </x-jet-modal>
    <x-jet-modal class="modal fade" wire:model="updateUnitPrice">
        <div class="modal-dialog ">
            <div class="modal-content p-32">
                <div class="modal-body p-0 row">
                    <div class="row col-12 p-0 m-0 mb-4">
                        <button type="button"
                                class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2"
                                wire:click.prevent="closeUnitModal" wire:loading.attr="disabled"
                                aria-label="Close"></button>
                    </div>
                    <div class="row col-12 p-0 m-0">
                        <form>
                            <div class="col-12 mb-24 row d-flex align-items-center pe-0">
                                <div class="col-4 col-md-3">
                                    <label for="Price" class="fs-16">Unit Price</label>
                                </div>
                                <div class="col-8 col-md-9 d-flex justify-content-end p-0">
                                    <span class="input-group-prepend col-auto">
                                        <button type="button"
                                                class="btn button-green btn-number"
                                                data-type="minus" data-field="QTY"
                                                wire:click="decrease('unitprice')">
                                            <svg class="icon icon-Minus-Icon" width="24" height="24" disabled>
                                                <use href="{{asset('img/icons.svg#icon-Minus-Icon')}}"></use>
                                            </svg>
                                        </button>
                                    </span>
                                    <input type="number" class="form-control default mx-2 w-75 text-center fw-bold"
                                           name="Price" wire:model="unitprice">
                                    <span class="input-group-append col-auto">
                                            <button type="button"
                                                    class="btn button-green btn-number"
                                                    data-type="plus" data-field="QTY"
                                                    wire:click="increase('unitprice')">
                                                <svg class="icon icon-Plus-Icon" width="24" height="24">
                                                    <use href="{{asset('img/icons.svg#icon-Plus-Icon')}}"></use>
                                                </svg>
                                            </button>
                                        </span>
                                </div>
                            </div>
                            <div class="col-12 mb-24">
                                <x-jet-input-error for="error" class="mt-2"/>

                                <hr class="m-0">
                            </div>
                            <div class="row col-12 m-0">
                                <button type="button"
                                        class="button-green w-100"
                                        wire:click="saveUnitPrice"
                                        wire:loading.attr="disabled">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </x-jet-modal>
    <x-jet-modal class="modal fade" wire:model="updateQuantity">
        <div class="modal-dialog ">
            <div class="modal-content p-32">
                <div class="modal-body p-0 row">
                    <div class="row col-12 p-0 m-0 mb-4">
                        <button type="button"
                                class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2"
                                wire:click.prevent="closeQuantityModal" wire:loading.attr="disabled"
                                aria-label="Close"></button>
                    </div>
                    <div class="row col-12 p-0 m-0">
                        <form>
                            <div class="col-12 mb-24 row d-flex align-items-center pe-0">
                                <div class="col-4 col-md-3">
                                    <label for="Price" class="fs-16">Quantity</label>
                                </div>
                                <div class="col-8 col-md-9 d-flex justify-content-end p-0">
                                    <span class="input-group-prepend col-auto">
                                    <button type="button"
                                            class="btn button-green btn-number"
                                            data-type="minus" data-field="QTY"
                                            wire:click="decrease('quantity')">
                                        <svg class="icon icon-Minus-Icon" width="24" height="24">
                                            <use href="{{asset('img/icons.svg#icon-Minus-Icon')}}"></use>
                                        </svg>
                                    </button>
                                    </span>
                                    <input type="number" class="form-control default mx-2 w-75 text-center fw-bold"
                                           name="Price" wire:model="quantity" onkeypress="return event.charCode >= 48">
                                    <span class="input-group-append col-auto">
                                        <button type="button" class="btn button-green btn-number"
                                                data-type="plus" data-field="QTY"
                                                wire:click="increase('quantity')">
                                            <svg class="icon icon-Plus-Icon" width="24" height="24">
                                                <use href="{{asset('img/icons.svg#icon-Plus-Icon')}}"></use>
                                            </svg>
                                        </button>
                                    </span>
                                </div>

                            </div>
                            <div class="col-12 mb-24">
                                <x-jet-input-error for="error" class="mt-2"/>
                                <hr class="m-0">
                            </div>
                            <div class="row col-12 m-0">
                                <button type="button"
                                        class="button-green w-100"
                                        wire:click="saveQuantity"
                                        wire:loading.attr="disabled">
                                    Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </x-jet-modal>
    @push('modals')
        @livewire('certificate.sell-certificate-modal')
        @livewire('certificate.cancel-sell-certificate-modal')
        @livewire("profile.payment.bank-form-modal",['bank'=>$bank])
    @endpush
</div>
@push('scripts')
    <script>

        document.addEventListener('livewire:load', function () {

            let maxDay = 14;
            let datas = [
                {x: 0, y: 30, date: '1/1/22'},
                {x: 1, y: 18, date: '2/1/22'},
                {x: 2, y: 39, date: '3/1/22'},
                {x: 3, y: 70, date: '4/1/22'},
                {x: 4, y: 79, date: '5/1/22'},
                {x: 5, y: 65, date: '6/1/22'},
                {x: 6, y: 90, date: '7/1/22'},
                {x: 7, y: 30, date: '8/1/22'},
                {x: 8, y: 60, date: '9/1/22'},
                {x: 9, y: 60, date: '10/1/22'},
                {x: 10, y: 50, date: '11/1/22'},
                {x: 11, y: 79, date: '12/1/22'},
                {x: 12, y: 65, date: '13/1/22'},
                {x: 13, y: 90, date: '14/1/22'},
            ];

            var ctx = document.getElementById('mainChartDetail').getContext('2d');
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
                            bodyFont: {size: 0},
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
                                    if (ticks.length == 14 || ticks.length == 30) {
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
