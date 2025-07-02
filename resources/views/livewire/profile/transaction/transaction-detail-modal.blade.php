<div>
    @if($transaction)
        <x-jet-dialog-modal wire:model="showModal" data-backdrop="static" data-keyboard="false"
                            id="transactionDetailsModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content p-4">
                    <div class="modal-body p-0 row">
                        <div class="row col-12 p-0 m-0">
                            <x-slot name="title">
                                <div class="row col-12 p-0 m-0">
                                    <h5 class="black-color col fw-bold mb-3">
                                        Transaction Details
                                    </h5>
                                    <button type="button"
                                            class="btn-close opacity-1 align-top align-self-start ms-auto col-auto me-2"
                                            wire:click.prevent="closeTransactionDetailModal"
                                            aria-label="Close"></button>
                                </div>
                            </x-slot>

                            <x-slot name="content">
                                <form class="row col-12 p-0 m-0">
                                    <div class="col-12 mb-24">
                                        <div class="transaction-info-block row w-100">
                                            <p class="col-12 fw-bold fs-16 mb-24">Details</p>
                                            <p class="col-12 fs-16 mb-3">Transaction date: <span
                                                        class="fw-bold">{{date('D, d M',strtotime($transaction->created_at))}}</span>
                                            </p>
                                            <p class="col-12 fs-16 mb-3">Carbon Credit type: <span
                                                        class="fw-bold">{{$transaction->certificate->project_type->type}}</span>
                                            </p>
                                            <p class="col-12 fs-16 mb-3">Amount: <span
                                                        class="fw-bold">${{price_format($transaction->amount)}}</span></p>
                                        </div>
                                        <div class="transaction-info-block row w-100">
                                            <p class="col-12 fw-bold fs-16 mb-24">From</p>
                                            <p class="col-12 fs-16 mb-3">Name: <span
                                                    class="fw-bold">{{$transaction->buyer->name}}</span>
                                            </p>
                                            <p class="col-12 fs-16 mb-3">IP Address: <span
                                                        class="fw-bold">{{$transaction->ip_address}}</span>
                                            </p>
                                            <p class="col-12 fs-16 mb-3">Card No.: <span
                                                        class="fw-bold">{{str_replace(range(0,9), "X", substr($transaction->card_detail->card_no, 0, -4)) .  substr($transaction->card_detail->card_no, -4)}}</span>
                                            </p>
                                        </div>
                                        <div class="transaction-info-block row w-100">
                                            <p class="col-12 fw-bold fs-16 mb-24">To</p>
                                            <p class="col-12 fs-16 mb-3">Name: <span
                                                        class="fw-bold">{{$transaction->seller->name}}</span>
                                            </p>
                                            <p class="col-12 fs-16 mb-3">Bank Name: <span
                                                        class="fw-bold">{{$transaction->seller_bank_detail ? $transaction->seller_bank_detail->name : Null}}</span>
                                            </p>
                                            <p class="col-12 fs-16 mb-3">Bank Account: <span
                                                        class="fw-bold">{{$transaction->seller_bank_detail ? preg_replace("/[A-Za-z0-9]/", "X", substr($transaction->seller_bank_detail->iban, 0, -4)) .  substr($transaction->seller_bank_detail->iban, -4)  : Null}}</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row col-12 m-0">
                                        <a class="button-green w-100 button-secondary fw-normal"
                                           href="javascript:void(0);" wire:click.prevent="closeTransactionDetailModal"
                                           aria-label="Close">Close</a>
                                    </div>
                                </form>
                            </x-slot>
                        </div>
                    </div>
                </div>
            </div>

            <x-slot name="footer">
            </x-slot>
        </x-jet-dialog-modal>
    @endif
</div>
