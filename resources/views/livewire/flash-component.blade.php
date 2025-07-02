<div class="flashContent">
    @if (session()->has('success'))
    <div>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="btn-close" data-bs-dismiss="alert" id="closeSuccessFlashMsg"></button>
            <strong>{!! session('success') !!}</strong>
        </div>
    </div>
    @endif
    @if (session()->has('error'))
    <div>
        <div class="alert alert-error alert-dismissible">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <strong>{!! session('error') !!}</strong>
        </div>
    </div>
    @endif

    @if (session()->has('warning'))
    <div>
        <div class="alert alert-warning alert-dismissible">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <strong>{!! session('warning') !!}</strong>
        </div>
    </div>
    @endif
    @if($showMessage)
    <div>
    <div class="alert alert-{{$type}} alert-dismissible">
        <button type="button" class="btn-close" data-bs-dismiss="alert" id="closeFlashMsg" wire:click.prevent="hideFlashMessage"></button>
        <strong>{!! $msg !!}</strong>
    </div>
    </div>
    @endif
    @push('scripts')
    <script>
            var showMessage = "{{$showMessage}}";
            if(showMessage){
                const el = document.getElementById("closeFlashMsg");
                setTimeout(function() {
                    el.click();
                }, 5000);
            }
    </script>

        @if(\Request::path() == 'dashboard' || \Request::path() == 'admin/dashboard')
            <script>
                const el = document.getElementById("closeSuccessFlashMsg");
                if(el !='undefined' && el !=null && el.length){
                    setTimeout(function() {
                        el.click();
                    }, 5000);
                }
            </script>
        @endif
    @endpush
</div>
