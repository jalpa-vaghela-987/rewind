<div>
<x-notify-component class="modal fade" wire:model="showModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4">
            <div class="modal-body p-0 row">
                <div class="row col-12 p-0 m-0">
                    <h5 class="black-color col fw-bold mb-4">
                        Notifications center
                        <button type="button"
                                class="btn-close opacity-1 float-right align-top align-self-start ms-auto col-auto me-2"
                                wire:click.prevent="closeNotificationModal()" aria-label="Close"></button>
                    </h5>
                </div>
                <div class="row col-12 p-0 m-0 mb-3 scrollable-content">
                    @foreach($notifications as $notification)
                        @if (\Carbon\Carbon::parse($notification['date'])->isToday())
                            <div class="day col-12">
                                <div class="date">Today</div>
                                @foreach($notification['data'] as $notify)
                                    <div class="d-flex justify-content-between mb-1 row">
                                        <p class="info col-9">{{\Carbon\Carbon::parse($notify['created_at'])->format('h:i A') }}
                                            : {!! $notify['data'] !!}</p>
                                        @if($notify['link'])
                                            <a href="{{$notify['link'] ? route($notify['link']) : null}}" class="btn button-green h-n col-3">Take me to
                                                {{$notify['link']}}</a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @elseif(\Carbon\Carbon::parse($notification['date'])->isYesterday())
                            <div class="day col-12">
                                <div class="date">Yesterday</div>
                                @foreach($notification['data'] as $notify)
                                    <div class="d-flex justify-content-between mb-1 row">
                                        <p class="info col-9">{{\Carbon\Carbon::parse($notify['created_at'])->format('h:i A') }}
                                            : {!! $notify['data'] !!}</p>
                                        @if($notify['link'])
                                            <a href="{{$notify['link'] ? route($notify['link']) : null}}" class="btn button-green h-n col-3">Take me to
                                                {{$notify['link']}}</a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="day col-12">
                                <div class="date">{{\Carbon\Carbon::parse( $notification['date'])->format('d-m-Y')}}</div>
                                @foreach($notification['data'] as $notify)
                                    <div class="d-flex justify-content-between mb-1 row">
                                        <p class="info col-9">{{\Carbon\Carbon::parse($notify['created_at'])->format('h:i A') }}
                                            : {!! $notify['data'] !!}</p>
                                        @if($notify['link'])
                                        <a href="{{$notify['link'] ? route($notify['link']) : null}}" class="btn button-green h-n col-3">Take me to
                                            {{$notify['link']}}</a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</x-notify-component>
</div>

@push('scripts')
    <script>
        document.addEventListener("livewire:load", function (event) {
            $('.btn-close').click(function(){
                location.reload();
            })
        });
    </script>
@endpush
