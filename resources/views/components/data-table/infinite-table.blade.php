<div class="col-12 p-0 row m-0 ">
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

        @if($hasMorePages)

            <tr
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
                class="testingbr"
            >

            </tr>
        @endif
        </tbody>
    </table>
</div>
