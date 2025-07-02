<div class="col-12 p-0 row m-0 table-responsive {{$class??''}}">
    @if(isset($title))
        <div class="row col mb-24 m-3 p-0 mt-1">
            <h6 class="fw-bold p-0 col-auto">{{ $title }}</h6>
        </div>
    @endif
    <table class="classic striped-table add-height table table-striped table-borderless table-fixed align-table">
        <thead>
            {{ $head }}
        </thead>
        <tbody>
            {{ $body }}
        </tbody>
    </table>

    @if($model instanceof \Illuminate\Pagination\LengthAwarePaginator )
    <div id="table_pagination" class="mt-4 pt-3 mb-4">
        {!! $model->links() !!}
    </div>
    @endif
</div>
