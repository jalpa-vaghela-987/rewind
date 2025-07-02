 <div class="row col-12 p-0 m-0 h-100">
    <div class="bg-white block-main row m-0 table-container p-0 h-100 pb-32 flex-column d-none d-sm-block">
        <div class="col-12 p-3 row m-0">
            <div class="search-wrap">
                <input type="search" class="base-search w-100" id="table_search" placeholder="Search" wire:model="search">
                <span class="icon">
                <svg class="icon icon-search" width="20" height="20">
                    <use href="{{asset('img/icons.svg#icon-search')}}"></use>
                </svg>
            </span>
            </div>
        </div>
        <div class="col-12 p-0 row m-0" >
            @if(!empty($lists))
                <div class="row col mb-2 m-4 p-0 mt-0">
                    <h6 class="fw-bold p-0 col-auto">Carbon Credits</h6>
                </div>

                <x-data-table.infinite-table :model="$lists" :columns="[]" :wantSearching="true" :dateFilter="true">
                    <x-slot name="title">
                    </x-slot>
                    <x-slot name="head">
                        <tr>
                            <th>{{ __('Type') }}</th>
                            <th >{{ __('Name') }}</th>
                            <th >{{ __('Country') }}</th>
                            <th >{{ __('Quantity') }}</th>
                            <th >{{ __('On Sell Qty') }}</th>
                            <th>{{ __('Current value 1D') }}</th>
                            <th >{{ __('Status') }}</th>
                            <th ></th>
                        </tr>
                    </x-slot>
                    <x-slot name="body">
                        @foreach($this->lists as $certificate)
                            <tr >
                                <td><a href="{{url('admin/certificate/')}}/{{$certificate['id']}}" class="text-decoration-none text-black d-flex align-items-center"> <svg width="32" height="32" class="icon icon-Forest-ERB mr-2">
                                            <use href="{{asset('img/icons.svg#'.$certificate['project_type']['image_icon'])}}"></use>
                                        </svg> {{$certificate['project_type']['type']}} </a></td>
                                <td>{{$certificate['name']}}</td>
                                <td>{{$certificate['country']['name']}}</td>
                                <td>{{$certificate['quantity']}}</td>
                                <td>{{$certificate['quantity']??0}}</td>
                                <td><div class="d-flex">
                                <span class="d-flex flex-column align-items-end justify-content-space-between">
                                    <span class="price fw-bold">${{ price_format($certificate['price'])}}</span>
{{--                                    <span class="statistic-price statistic-decrease d-flex  ms-1">--}}
                                    {{--                                    <svg class="icon icon-decrease me-1" width="16" height="16">--}}
                                    {{--                                        <use href="./img/icons.svg#icon-decrease"></use>--}}
                                    {{--                                    </svg> -0.38%</span>--}}
                                </span>
                                        <span class="small-graph ms-2">
                                    <canvas class="small-graph1"></canvas>
                                </span>
                                    </div>
                                </td>
                                <td>
                                    @if($certificate['status'] == 1)
                                        <div class="status-button pending">
                                            Pending
                                        </div>
                                    @elseif($certificate['status'] == 2)
                                        <div class="status-button approved">
                                            Approved
                                        </div>
                                    @elseif($certificate['status'] == 3)
                                        <div class="status-button onsell">
                                            On Sell
                                        </div>
                                    @else
                                        <div class="status-button notapproved">
                                            Declined
                                        </div>
                                    @endif
                                </td>
                                <td x-data="{}">
                                    <div>
                                        <a href="javascript:void(0)" class="button-green sell-table"
                                           x-on:click="$wire.openModal({{ $certificate }})"
                                        >View</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                    <x-slot name="hasMorePages">
                        {{$hasMorePages}}
                    </x-slot>
                </x-data-table.infinite-table>

            @endif
        </div>

    </div>
     <div class="index-list-sm d-block d-sm-none p-0">
         <div class="mb-2">
             <div class="search-wrap">
                 <input type="search" class="base-search w-100" id="table_search" placeholder="Search"
                        wire:model="search">
                 <span class="icon">
                    <svg class="icon icon-search" width="20" height="20">
                        <use href="{{asset('img/icons.svg#icon-search')}}"></use>
                    </svg>
                </span>
             </div>
         </div>
         <h4 class="fw-bold p-0 mb-4">Carbon Credits</h4>
         @forelse ($lists as $certificate)
             <div class="card-el p-3 mb-3">
                 <div class="card-header d-flex justify-content-between">
                     <a href="{{url('admin/certificate/')}}/{{$certificate['id']}}" class="text-decoration-none text-black">
                         <div class="d-flex align-items-center">
                             <svg class="icon {{$certificate['project_type']['image_icon']}} me-2" width="32"
                                  height="32">
                                 <use href="../img/icons.svg#{{$certificate['project_type']['image_icon']}}">
                                 </use>
                             </svg>
                             <div class="d-flex flex-column">
                                 <div class="title fw-bold">{{$certificate['name']}}</div>
                                 <div class="title">{{$certificate['project_type']['type']}}</div>
                             </div>
                         </div>
                     </a>
                     <span class="d-flex flex-column align-items-end justify-content-space-between">
                    <span class="price fw-bold">  ${{ price_format($certificate['price'])}}</span>
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
                         <span class="result fw-bolder"> {{$certificate['country']['name']}}</span>
                     </p>
                     <p class="d-flex justify-content-between align-items-center mb-2">
                         <span class="title fw-bold text-black-50 fw-bolder">Quantity:</span>
                         <span class="result fw-bolder"> {{ $certificate['quantity']}}</span>
                     </p>
                     <p class="d-flex justify-content-between align-items-center">
                         <span class="title fw-bold text-black-50 fw-bolder">Status:</span>

                         @if($certificate['status'] == 1)
                             <span class="status-button pending  fw-normal">
                                Pending
                            </span>
                         @elseif($certificate['status'] == 2)
                             <span class="status-button approved  fw-normal">
                                Approved
                            </span>
                         @elseif($certificate['status'] == 3)
                             <span class="status-button onsell  fw-normal">
                                On Sell
                            </span>
                         @else
                             <span class="status-button notapproved  fw-normal">
                                Declined
                            </span>
                         @endif
                     </p>

                 </div>
                 <hr class="opacity-25">
                 <div class="d-flex justify-content-end">
                     <a href="javascript:void(0)" class="button-green sell-table"
                        wire:click="openModal({{ $certificate['id'] }})"
                     >View</a>
                 </div>
             </div>
         @empty
         @endforelse
         @if($hasMorePages)
             <div
                 x-data="{
                                                   init () {
                                                         let observer = new IntersectionObserver((entries) => {
                                                                        entries.forEach(entry => {
                                                                        if (entry.isIntersecting) {
                                                                        @this.call('loadData')
                                                                        }
                                                                        })
                                                        }, {
                                                            root: null
                                                   });
                                                   observer.observe(this.$el);
                                                    }
                                        }"
             >

             </div>
         @endif
     </div>
     <x-jet-modal class="modal fade" wire:model="viewCertificate" >
         <div class="modal-dialog modal-dialog-centered">
             <div class="modal-content p-4">
                 <div class="modal-body p-0 row">
                     <div class="row col-12 p-0 m-0">

                         <h5 class="black-color col fw-bold mb-4">
                             Review Carbon Credit for Approval
                             <button type="button" class="btn-close opacity-1 float-right align-top align-self-start ms-auto col-auto me-2" wire:click.prevent="closeModal()" aria-label="Close"></button>
                         </h5>


                     </div>

                     {{--<form class="row col-12 p-0 m-0">--}}
                     <div class="col-12 mb-30">
                         <label for="project_name" class="form-label p-0 black-color">{{ __('Project Type') }}</label>
                         <input class="form-control default" value="{{ $projectType }}" disabled/>
                     </div>
                     <div class="col-12 mb-30">

                         <label for="project_name" class="form-label p-0 black-color">{{ __('Project Name') }}</label>
                         <input class="form-control default" value="{{ $title }}" disabled/>
                     </div>
                     <div class="col-12 mb-30">
                         <label for="country_id" class="form-label p-0 black-color">{{ __('Country') }}</label>
                         <input class="form-control default" value="{{ $country }}" disabled>
                     </div>
                     <div class="col-12 mb-30">
                         <label for="country_id" class="form-label p-0 black-color">{{ __('Quantity') }}</label>
                         <input class="form-control default" value="{{ $quantity }}" disabled>
                     </div>
                     <div class="col-12 mb-30">
                         <label for="country_id" class="form-label p-0 black-color">{{ __('Price') }}</label>
                         <input class="form-control default" value="${{ price_format($price) }}" disabled>
                     </div>
                     <div class="col-12 mb-30" >
                         <x-jet-label for="project_year" class="form-label p-0 black-color" value="{{ __('Project Year') }}" />
                         <input class="form-control default" value="{{ $project_year }}" disabled>
                         <x-jet-input-error for="project_year" class="mt-2" />
                     </div>

                     <div class="col-12 mb-30" >
                         <x-jet-label for="vintage" class="form-label p-0 black-color" value="{{ __('Vintage') }}" />
                         <input class="form-control default" value="{{ $vintage }}" disabled>
                         <x-jet-input-error for="vintage" class="mt-2" />

                     </div>

                     <div class="col-12 mb-30">
                         <x-jet-label for="total_size" class="form-label p-0 black-color" value="{{ __('Total Size (in Acers)') }}" />
                         <input class="form-control default" value="{{ $total_size }}" disabled>
                         <x-jet-input-error for="total_size" class="mt-2" />
                     </div>
                     <div class="col-12 mb-30">
                         <x-jet-label for="lattitude" class="form-label p-0 black-color" value="{{ __('Latitude') }}" />
                         <x-jet-input id="lattitude" type="number"   maxlength="16" class="form-control default" wire:model.defer="lattitude" autocomplete="lattitude"/>
                         <x-jet-input-error for="lattitude" class="mt-2" />
                     </div>
                     <div class="col-12 mb-30">
                         <x-jet-label for="longitude" class="form-label p-0 black-color" value="{{ __('Longitude') }}" />
                         <x-jet-input id="longitude" type="number"  onKeyPress="if(this.value.length==16) return false;" maxlength="16" class="form-control default" wire:model.defer="longitude" autocomplete="longitude"/>
                         <x-jet-input-error for="longitude" class="mt-2" />
                     </div>
                     <div class="col-12 mb-30">
                         <x-jet-label for="verify_by" class="form-label p-0 black-color" value="{{ __('Verified By') }}" />
                         <x-jet-input id="verify_by" type="text" maxlength="16" class="form-control default" wire:model.defer="verify_by" autocomplete="verify_by"/>
                         <x-jet-input-error for="verify_by" class="mt-2" />
                     </div>
                     <div class="col-12 mb-30">
                         <x-jet-label for="registry_id" class="form-label p-0 black-color" value="{{ __('Registry Id') }}" />
                         <x-jet-input id="registry_id" type="text"  maxlength="16" class="form-control default" wire:model.defer="registry_id" autocomplete="registry_id"/>
                         <x-jet-input-error for="registry_id" class="mt-2" />
                     </div>
                     <div class="col-12 mb-30">
                         <label for="country_id" class="form-label p-0 black-color">{{ __('Approving Body') }}</label>
                         <input class="form-control default" value="{{ $approving_body }}" disabled>
                     </div>
                     <div class="col-12 mb-30">
                         <label for="country_id" class="form-label p-0 black-color">{{ __('Link to Carbon Credit') }}</label>
                         <input class="form-control default" value="{{ $link_to_certificate }}" disabled>
                     </div>
                     <div class="col-12 d-flex flex-column justify-content-end mt-2">
                         <a class="button-green w-100 button-send fw-normal mb-40" wire:click="showCertificate({{$certificateId}})" href="javascript:void(0)">
                             @if($status == 1)
                                 {{ __('Continue to Edit Carbon Credit') }}
                             @else
                                 {{ __('Continue to Approve Carbon Credit') }}
                             @endif
                             <svg class="icon icon-arrow-right ms-2" width="16" height="16">
                                 <use href="{{asset('img/icons.svg#icon-arrow-right')}}"></use>
                             </svg></a>
                         <a class="button-secondary w-100" wire:click="declineCertificate({{$certificateId}})" href="javascript:void(0)" >Decline Carbon Credit</a>
                     </div>
                 </div>
             </div>
         </div>
     </x-jet-modal>
</div>

