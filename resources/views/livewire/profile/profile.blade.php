
<div>
    <div class="row col-12 p-0 m-0 h-100">
        <div class="bg-white block-main p-3 row m-0 table-container h-100 flex-column">
            <div class="">
                <div class="row col-12 mb-3 p-0">
                    <h6 class="fw-bold col-auto">Profile</h6>
                </div>
                <div class="col row pe-0">
                    <ul class="nav basic-style row mx-0 p-0 mw-1000" id="detailsTabs" role="tablist">
                        <li class="nav-item col text-center p-0" role="presentation">
                            <a class="nav-link @if($activeTab == 'details') active @endif" id="details-tab" data-bs-toggle="tab"
                                data-bs-target="#details" type="button" role="tab" aria-controls="details"
                                aria-selected="true">My Details</a>
                        </li>
                        <li class="nav-item col text-center p-0" role="presentation">
                            <a class="nav-link @if($activeTab == 'payment') active @endif" id="payment-tab" data-bs-toggle="tab"
                                data-bs-target="#payment" type="button" role="tab" aria-controls="payment"
                                aria-selected="false" tabindex="-1">Manage Payments</a>
                        </li>

                        <li class="nav-item col text-center p-0" role="presentation">
                            <a class="nav-link @if($activeTab == 'transaction') active @endif" id="transaction-tab" data-bs-toggle="tab"
                                data-bs-target="#transaction" type="button" role="tab"
                                aria-controls="transaction" aria-selected="false"
                                tabindex="-1">Transactions</a>
                        </li>
                        <li class="nav-item col text-center p-0" role="presentation">
                            <a class="nav-link @if($activeTab == 'log') active @endif" id="log-tab" data-bs-toggle="tab" data-bs-target="#log"
                                type="button" role="tab" aria-controls="log" aria-selected="false"
                                tabindex="-1">Activity
                                Log</a>
                        </li>
                    </ul>
                    <!-- OLD -->
                    <div id="detailsTabsContent" class="tab-content mb-24 pe-0">
                        <div class="tab-pane fade @if($activeTab == 'details') show active @endif row" id="details" role="tabpanel"
                                aria-labelledby="details-tab">
                            <div class="row col-12 d-flex justify-content-between align-items-end pe-0 ">
                                @livewire("profile.detail.user.user-detail-form")
                                @if(!empty($user->company))
                                    @livewire("profile.detail.company.company-detail-form")
                                @else
                                <div class="col-6 d-flex align-items-center justify-content-end">
                                    <button type="button" class="btn button-green px-4"
                                        wire:click="$emit('openCloseAddNewCompanyModal')">
                                        <svg class="icon icon-plus me-2" width="16" height="16">
                                            <use href="{{asset('img/icons.svg#icon-plus')}}"></use>
                                        </svg>
                                        Add New Company
                                    </button>
                                    @push('modals')
                                    @livewire("profile.detail.company.add-new-company-modal")
                                    @endpush
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane fade row @if($activeTab == 'payment') show active @endif" id="payment" role="tabpanel" aria-labelledby="payment-tab">
                            @livewire("profile.payment.manage-payment")
                        </div>
                        <div class="tab-pane fade row @if($activeTab == 'transaction') show active @endif" id="transaction" role="tabpanel"
                                    aria-labelledby="transaction-tab">
                            @livewire("profile.transaction.my-transactions")
                        </div>
                        <div class="tab-pane fade row @if($activeTab == 'log') show active @endif" id="log" role="tabpanel" aria-labelledby="log-tab">
                            @livewire("profile.activity.my-activity-log")
                        </div>
                    </div>
                    <!-- OLD -->
                </div>
            </div>
        </div>
    </div>
    <?php /* @push('scripts')
    <script src="https://cdn.withpersona.com/dist/persona-v4.7.1.js"></script>
    <script>
    const client = new Persona.Client({
        templateId: "{{env('PERSONA_GOVT_ID_TEMPLATE_ID')}}",
        // environmentId: 'env_ARsDKdtTAM9QimZzJMpvMTaV',
        environment: 'sandbox',
        referenceId: "{{$user->id}}",
        onReady: () => client.open(),
        onComplete: ({ inquiryId, status, fields }) => {
        console.log(`Completed inquiry ${inquiryId} with status ${status}`);
        }
    });
    </script>
    @endpush*/ ?>
</div>
