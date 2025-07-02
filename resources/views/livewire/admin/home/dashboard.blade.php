<div>

    <div class="row">
        <div class=" col-12 p-0 m-0 gap-4">
            <div class="bg-white block-main row m-2 p-2">
                <div class="col-12 p-3 row m-0">
                    <div class="search-wrap">
                        <input type="search" class="base-search w-100" id="table_search" placeholder="Search">
                        <span class="icon">
                            <svg class="icon icon-search" width="20" height="20">
                                <use href="{{asset('img/icons.svg#icon-search')}}"></use>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 p-0 m-0 gap-4">
            @livewire('admin.home.dashboard-overview')
        </div>
    </div>
    <div class="d-none d-sm-block">
        <div class="row col-12 p-0 m-0 gap-4 mh-75vh">
            <div class="col gap-4 d-flex flex-column p-0 h-100">
                @livewire('admin.home.new-registrants')
                @livewire('admin.home.dashboard-active-user-list')
                @livewire('admin.home.dashboard-certificates')
            </div>
            <div class="col gap-4 d-flex flex-column p-0 h-100">
                @livewire('admin.home.dashboard-bids')
                @livewire('admin.home.dashboard-deals')
            </div>
        </div>
    </div>
    <div class="dashboard-sm d-block d-sm-none p-0">
        <div class="col row pe-0">
            <ul class="nav basic-style row mx-0 p-0 mb-30 mw-1000 d-flex align-items-end" id="trendingTabs"
                role="tablist">
                <li class="nav-item col-auto text-center p-0" role="presentation">
                    <a class="nav-link active  fs-12" id="registrant-tab" data-bs-toggle="tab"
                       data-bs-target="#registrant"
                       type="button" role="tab" aria-controls="registrant" aria-selected="true">Approve New
                        Registrants</a>
                </li>
                <li class="nav-item col-2 text-center p-0" role="presentation">
                    <a class="nav-link fs-12" id="bids-tab" data-bs-toggle="tab" data-bs-target="#bids"
                       type="button"
                       role="tab" aria-controls="bids" aria-selected="false" tabindex="-1">Bids</a>
                </li>

                <li class="nav-item col text-center p-0" role="presentation">
                    <a class="nav-link fs-12" id="users-tab" data-bs-toggle="tab" data-bs-target="#users"
                       type="button"
                       role="tab" aria-controls="users" aria-selected="false" tabindex="-1">Active User List
                    </a>
                </li>
                <li class="nav-item col-2 text-center p-0" role="presentation">
                    <a class="nav-link fs-12" id="deals-tab" data-bs-toggle="tab" data-bs-target="#deals"
                       type="button"
                       role="tab" aria-controls="deals" aria-selected="false" tabindex="-1">Deals</a>
                </li>
                <li class="nav-item col text-center p-0" role="presentation">
                    <a class="nav-link fs-12" id="carbons-tab" data-bs-toggle="tab" data-bs-target="#carbons"
                       type="button" role="tab" aria-controls="carbons" aria-selected="false" tabindex="-1">Carbon
                        Credits</a>
                </li>
            </ul>
            <div id="registrantTabsContent" class="tab-content mb-24 pe-0">
                <div class="tab-pane fade show active row" id="registrant" role="tabpanel"
                     aria-labelledby="registrant-tab">
                    @livewire('admin.home.new-registrants')
                </div>
                <div class="tab-pane fade row" id="bids" role="tabpanel" aria-labelledby="bids-tab">
                    @livewire('admin.home.dashboard-bids')
                </div>
                <div class="tab-pane fade row" id="users" role="tabpanel" aria-labelledby="users-tab">
                    @livewire('admin.home.dashboard-active-user-list')
                </div>

                <div class="tab-pane fade row" id="deals" role="tabpanel" aria-labelledby="deals-tab">
                    @livewire('admin.home.dashboard-deals')
                </div>
                <div class="tab-pane fade row" id="carbons" role="tabpanel" aria-labelledby="carbons-tab">
                    @livewire('admin.home.dashboard-certificates')
                </div>


            </div>
        </div>
    </div>

</div>

@push('modals')
    @livewire('admin.user.user-preview-and-approve-modal')
@endpush()
