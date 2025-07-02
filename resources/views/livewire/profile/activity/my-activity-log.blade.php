<div>
    <div class="col row p-3">
        <div class="row col-12">
            <div class="ms-auto col-auto">
                <div class="btn-group btn-row" role="group"
                    aria-label="Basic example">
                    @foreach($activity_duration_dropdown as $key=>$name)
                        <button type="button"
                            class="activity-log-btn btn {{($activity_duration==$key)?'active':''}}"
                            wire:click.prevent="changeActivityDuration('{{$key}}')">
                            {{ $name }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="row col-12 green-scroll">
            @forelse ($activities as $day =>$dayActivities)
                <div class="block-day row">
                    <p class="fw-bold fs-20 black-color title-day mb-3">{{ $day }}</p>
                    @foreach ($dayActivities as $activity)
                    <p class="log-line fs-16">
                        <span class="text-green-50">{{$activity->time}}</span>
                        {!!ucfirst($activity->description)!!}
                    </p>
                    @endforeach
                </div>
            @empty
                <div class="text-center">
                    <p>No any activity found</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener("livewire:load", function (event) {
        $('.activity-log-btn').click(function(){
            $('#log .green-scroll')[0].scrollTo(0, 0)
        })
    });
</script>
@endpush
