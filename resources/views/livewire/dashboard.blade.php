<div>
    <div class="row w-100 m-0 gap-4 d-none d-sm-flex">
        <div class="row col-12 p-0 m-0 gap-4">
            <div class="col gap-4 d-flex flex-column p-0 h-100">
                @livewire('dashboard.index-list')
            </div>
        </div>

        <div class="row col-12 p-0 m-0 gap-4 mh-75vh">
            <div class="col gap-4 d-flex flex-column p-0 h-100">
                @livewire('dashboard.latest-purchase')
                @livewire('dashboard.latest-sale')
            </div>

            <div class="col-12 col-lg-5 gap-4 d-flex flex-column p-0 h-100">
                    @livewire('dashboard.bids')
            </div>
        </div>
    </div>

    <div class="dashboard-sm d-block d-sm-none p-0">
        <div class="col row pe-0">
            <ul class="nav basic-style row mx-0 p-0 mb-30 mw-1000 d-flex align-items-end" id="trendingTabs" role="tablist">
                <li class="nav-item col-auto text-center p-0" role="presentation">
                    <a class="nav-link active  fs-12" id="trending-tab" data-bs-toggle="tab" data-bs-target="#trending"
                       type="button" role="tab" aria-controls="trending" aria-selected="true">Trending</a>
                </li>
                <li class="nav-item col text-center p-0" role="presentation">
                    <a class="nav-link fs-12" id="purchases-tab" data-bs-toggle="tab" data-bs-target="#purchases"
                       type="button" role="tab" aria-controls="purchases" aria-selected="false" tabindex="-1">Latest
                        Purchases</a>
                </li>
                <li class="nav-item col text-center p-0" role="presentation">
                    <a class="nav-link fs-12" id="sales-tab" data-bs-toggle="tab" data-bs-target="#sales" type="button"
                       role="tab" aria-controls="sales" aria-selected="false" tabindex="-1">Latest Sales</a>
                </li>
                <li class="nav-item col-2 text-center p-0" role="presentation">
                    <a class="nav-link fs-12" id="bids-tab" data-bs-toggle="tab" data-bs-target="#bids" type="button"
                       role="tab" aria-controls="bids" aria-selected="false" tabindex="-1">Bids</a>
                </li>
            </ul>
            <div id="trendingTabsContent" class="tab-content mb-24 pe-0">
                <div class="tab-pane fade show active row" id="trending" role="tabpanel" aria-labelledby="trending-tab">
                    @livewire('dashboard.index-list')
                </div>
                <div class="tab-pane fade row" id="purchases" role="tabpanel" aria-labelledby="purchases-tab">
                    @livewire('dashboard.latest-purchase')
                </div>
                <div class="tab-pane fade row" id="sales" role="tabpanel" aria-labelledby="sales-tab">
                    @livewire('dashboard.latest-sale')
                </div>
                <div class="tab-pane fade row" id="bids" role="tabpanel" aria-labelledby="bids-tab">
                    @livewire('dashboard.bids')
                </div>

            </div>
        </div>
    </div>
</div>

