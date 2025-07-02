<div>
    <div class="bg-white block-main row m-2 p-2 pt-3">
        <div class="row col mb-2 ms-2">
            <h6 class="fw-bold p-0">Stats Overview</h6>
        </div>
        <div class=" row m-2 gap-2">
            @foreach ($overViewData as $title   =>  $count)
                <div class="col border p-3 bg-white">
                    <p class="fw-bold fs-16 mb-2">
                        {{$title}}
                    </p>
                    <h4 class="fw-bold">{{floor( $count ) != $count? number_format($count,2):$count}}</h4>
                </div>    
            @endforeach 
        </div>
    </div>
</div>