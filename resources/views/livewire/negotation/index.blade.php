<div>
    <div class="row col-12 p-0 m-0 h-100">
        <div class="bg-white block-main row m-0 table-container h-100 p-3 flex-column pt-0">

            <div class="col-12 p-0 row m-0">
                <div class="row col m-3 p-0">
                    <h6 class="fw-bold p-0 col-auto">Offers</h6>

                </div>
            </div>
             <div class="col-12 p-0 row m-0"  wire:ignore> <!-- -->
                 <x-data-table.negotation :columns="[]" :wantSearching="true" :dateFilter="true">
                     <x-slot name="head">
                         <tr>
                             <th data-filter-control-placeholder="Name" data-field="Name" data-width="100px" rowspan="1" colspan="1" style="width: 103.383px;">
                                 Name</th>
                             <th data-filter-control-placeholder="Date" data-field="Date" data-width="100px" style="width: 84px;">
                                 Date</th>
                             <th data-filter-control-placeholder="CertificateName" data-field="CertificateName" style="width: 130.742px;">
                                 Carbon Credit name</th>
                             <th data-filter-control-placeholder="PriceQuantity" data-field="PriceQuantity" style="width: 156.898px;">
                                 Initial Price/Quantity
                             </th>
                             <th data-filter-control-placeholder="BidQuantity" data-field="BidQuantity" style="width: 158.516px;">
                                 Offered Bid/Quantity
                             </th>
                             <th data-filter-control-placeholder="Status" data-field="Status" style="width: 89.5078px;">
                                 Status</th>
                             <th data-filter-control-placeholder="Reply" data-field="Reply" style="width: 338px;">
                                 Your reply
                             </th>
                         </tr>
                     </x-slot>
                     <x-slot name="body">
                         @php
                             $i = 1;
                         @endphp
                         @foreach($bids as $index=>$bid)
                             @php
                                 $ariaControls ='';
                                 if(!empty($bid->counterOffer->toArray())){
                                     foreach($bid->counterOffer as $j => $counterOffer){
                                         $j+=1;
                                         if($i==0){
                                             $ariaControls .= 'node-'.$i.'-'.$j;
                                         }else{
                                             $ariaControls .= ' node-'.$i.'-'.$j;
                                         }
                                     }
                                 }
                             @endphp
                             <tr
                                 class="treegrid-{{$i}} {{($bid->counterOffer->count() == 0) ? 'deactivated-drop' : ''}}"
                                 id="node-{{$i}}"
                             >
                                 <td>{{ ($bid->user_id == auth()->id()) ? $bid->certificate->user->name.'(owener)' : $bid->user->name}}</td>
                                 <td>{{\Carbon\Carbon::parse($bid->created_at)->format('d M, g:i A')}}</td>
                                 <td>{{$bid->certificate->name}}</td>
                                 <td>${{price_format($bid->sell_certificate->price_per_unit)}} / Quantity {{$bid->initial_quantity}}</td>
                                 <td>${{$bid->rate}} / Quantity {{$bid->unit}}</td>
                                 <td>
                                     @if($bid->status == 0)
                                         <div class="status-button pending">
                                             Pending
                                         </div>
                                     @elseif($bid->status == 1)
                                         <div class="status-button approved">
                                             Approved
                                         </div>
                                     @elseif($bid->status == 2)
                                         <div class="status-button notapproved">
                                             Declined
                                         </div>
                                     @elseif($bid->status == 3)
                                         <div class="status-button offered">
                                             Offered
                                         </div>
                                     @elseif($bid->status == 4)
                                         <div class="status-button canceled">
                                             Canceled
                                         </div>
                                     @endif
                                 </td>
                                 <td x-data="{}">
                                     <div class="row m-0 p-0 gap-2" >
                                         <select
                                             data-bid-id="{{$bid->id}}"
                                             name="negotation-form"
                                             class="selectpicker button-green negotation-form ms-0 col-auto changeStatus"
                                             data-container="body"
                                             title="reply"
                                             wire:model.defer="status"
                                             {{($bid->status != 0 || $bid->user_id == auth()->id()) ? 'disabled' : '' }}>
                                             <option value="1">Accept</option>
                                             <option value="2">Decline</option>
                                             <option value="3">Counter Offer</option>
                                             <option value="4">Cancel</option>
                                         </select>
                                         <form action=""
                                               class="form-counter-offer col-auto p-0 m-0 visually-hidden counter-offer-block">
                                             <input type="number" min="1" wire:model.defer="price" class="form-control default" placeholder="{{  __('Total Price') }}">
                                             <input type="number" min="1" wire:model.defer="quantity" class="form-control default" placeholder="{{  __('Quantity') }}">
                                              <a class="button-green   sell-table save-negotation-form"
                                                 href="javascript:void(0);" disabled wire:click="openCloseOfferedModal({{$bid->id}})">Save</a>
                                         </form>
                                     </div>
                                 </td>
                             </tr>
                             @if(!empty($bid->counterOffer->toArray()))
                                 @php
                                     $k = $i;
                                 @endphp
                                 @foreach($bid->counterOffer as $j => $counterOffer)
                                     @php
                                         $j += 1;
                                         $i += 1;
                                     @endphp
                                     <tr class="treegrid-{{$i}} treegrid-parent-{{$k}}" id="node-{{$k.'-'.$j}}">
                                         <td>{{$counterOffer->user->name}}</td>
                                         <td>{{\Carbon\Carbon::parse($counterOffer->created_at)->format('d M, g:i A')}}</td>
                                         <td>{{$bid->certificate->name}}</td>
                                         <td>${{$bid->sell_certificate->price_per_unit}} / Quantity {{$bid->initial_quantity}}</td>
                                         <td>${{price_format(($counterOffer->amount / $counterOffer->quantity))}} / Quantity {{$counterOffer->quantity}}</td>
                                         <td>
                                             @if($counterOffer->status == 0)
                                                 <div class="status-button pending">
                                                     Pending
                                                 </div>
                                             @elseif($counterOffer->status == 1)
                                                 <div class="status-button approved">
                                                     Approved
                                                 </div>
                                             @elseif($counterOffer->status == 2)
                                                 <div class="status-button notapproved">
                                                     Declined
                                                 </div>
                                             @elseif($counterOffer->status == 3)
                                                 <div class="status-button offered">
                                                     Offered
                                                 </div>
                                             @elseif($counterOffer->status == 4)
                                                 <div class="status-button canceled">
                                                     Canceled
                                                 </div>
                                             @endif
                                         </td>
                                         <td x-data="{}">
                                             @if($counterOffer->user_id != auth()->user()->id && $counterOffer->status == 0)
                                                 <div class="row m-0 p-0 gap-2" >
                                                     <select
                                                         name="negotation-form"
                                                         class="selectpicker button-green negotation-form ms-0 col-auto changeStatus"
                                                         data-container="body"
                                                         title="reply"
                                                         data-bid-id="{{$bid->id}}"
                                                         wire:model.defer="status">
                                                         <option value="1">Accept</option>
                                                         <option value="2">Decline</option>
                                                         <option value="3">Counter Offer</option>
                                                         @if($bid->certificate->user->id == auth()->user()->id)
                                                             <option value="4">Cancel</option>
                                                         @endif
                                                     </select>
                                                     <form action=""
                                                           class="form-counter-offer col-auto p-0 m-0 visually-hidden counter-offer-block"
                                                     >
                                                         <input type="number" min="1" wire:model.defer="price" class="form-control default" placeholder="{{  __('Total Price') }}">
                                                         <input type="number" min="1" wire:model.defer="quantity" class="form-control default" placeholder="{{  __('Quantity') }}">
                                                         <a class="button-green   sell-table save-negotation-form"
                                                            href="javascript:void(0);" disabled wire:click.prevent="openCloseOfferedModal">Save</a>
                                                     </form>
                                                 </div>
                                             @else
                                                 <div class="row m-0 p-0 gap-2" >
                                                     <select name="negotation-form" class="selectpicker button-green negotation-form ms-0 col-auto" data-container="body" title="reply" disabled>
                                                     </select>
                                                 </div>
                                             @endif
                                         </td>
                                     </tr>
                                 @endforeach
                             @endif
                             @php $i++ @endphp
                         @endforeach
                     </x-slot>
                 </x-data-table.negotation>
            </div>
        </div>

        @if(!empty($selectedBid))
            @if($status == 1)
                @livewire("negotation.modal.accept-modal", ['selectedBid' => $selectedBid, 'counterOfferId' => $counterOfferId])
            @endif
            @if($status == 2)
                @livewire("negotation.modal.decline-modal", ['selectedBid' => $selectedBid, 'counterOfferId' => $counterOfferId])
            @endif
            @if($status == 3 && $offeredModal)
                @livewire("negotation.modal.offered-modal", ['selectedBid' => $selectedBid, 'counterOfferId' => $counterOfferId, 'price' => $price, 'quantity' => $quantity, 'offeredModal' => $offeredModal])
            @endif
            @if($status == 4)
                @livewire("negotation.modal.cancelled-modal", ['selectedBid' => $selectedBid])
            @endif
        @endif
    </div>
    @push('scripts')
        <script>
        document.addEventListener("livewire:load", function (event) {
            $(document)
            .off('changed.bs.select','.changeStatus')
            .on('changed.bs.select','.changeStatus', function (e) {
                let bidId = $(this).attr('data-bid-id');
                if(bidId!= undefined){
                    Livewire.emit('getStatus',bidId);
                }
            });
            if ('.negotation-form '.length) {
                $(document)
                .off('keyup', '.form-counter-offer input')
                .on('keyup', '.form-counter-offer input', function () {
                    let price = $(this).val();
                    let quantity = $(this).siblings('input').val();
                   if(price!='' && price>0 && quantity!='' && quantity>0) {
                       $(this).closest('.form-counter-offer').find('.button-green').attr('disabled', false);
                   } else {
                       $(this).closest('.form-counter-offer').find('.button-green').attr('disabled', true);
                   }
                });

                $(document)
                .off('changed.bs.select', '.negotation-form')
                .on('changed.bs.select', '.negotation-form', function (e) {
                    if ($(this).prop('tagName') == 'SELECT') {
                        if ($(this).val() == '3') {
                            $($(this).closest('td')[0]).find('.form-counter-offer').removeClass('visually-hidden');
                        } else {
                            $($(this).closest('td')[0]).find('.form-counter-offer').addClass('visually-hidden');
                        }
                    }
                });
            }
        });
        </script>
    @endpush
</div>
