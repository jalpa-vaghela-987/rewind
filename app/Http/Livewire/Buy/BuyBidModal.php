<?php

namespace App\Http\Livewire\Buy;

use App\Models\BankDetail;
use App\Models\Bid;
use App\Models\Certificate;
use App\Models\SellCertificate;
use App\Models\Subscription;
use App\Notifications\SendMessageNotification;
use Livewire\Component;

class BuyBidModal extends Component
{
    protected $listeners = ['openBuyBidModal','reRenderParent'=>'reRenderComponent','finalBuy'=>'payNow'];
    public $showModal  =   false;
    public $selectedCertificate;
    public $title   =   null;
    public $activeTab = 'buy';
    public $showData = 'quantity';
    public $bidShowData = 'quantity';
    public $rate    =   0;
    public $amount    =   0;
    public $bid_units    =   0;
    public $price_difference    =   0;

    public $pricePerUnit    =   0;
    public $cost    =   0;
    public $units   =   0;
    public $amount_difference   =   0;
    public $price_average   =   0;
    public $primary_card_id =   0;
    public $total_reamining_units = 0;
    public $max_limit_amount=0;
    public function rules(){
        if($this->activeTab=="buy"){
            $rules  =   [
                'units' => ['required', 'integer','gt:0'],
            ];
            if ( !empty($this->selectedCertificate) ) {
                $rules['units'][] = 'lte:'.$this->selectedCertificate->remaining_units;
            }
        }else{
            $rules  =   [
                'rate' => ['required', 'numeric','gt:0'],
                'bid_units'=> ['required_if:bidShowData,units', 'integer','gt:0'],
                'amount'=> ['required_if:bidShowData,cost', 'numeric','gt:0'],
            ];
            if ( !empty($this->selectedCertificate) ) {
                $rules['rate'][] = 'lte:'.$this->pricePerUnit;
                $rules['bid_units'][] = 'lte:'.$this->selectedCertificate->remaining_units;
                $rules['amount'][] = 'lte:'.round((float)$this->pricePerUnit * (int)$this->bid_units);
            }
        }
        return $rules;
    }

    public function messages(){
        return [
            'units.required' => 'Quantity is required',
            'units.integer' => 'Quantity must be an integer',
            'units.lt' => 'You can buy maximum '.$this->selectedCertificate->remaining_units.'  units!',
            'units.gt' => 'Please select atleast 1 unit to buy!',

            'bid_units.required_if' => 'Quantity is required',
            'bid_units.integer' => 'Quantity must be an integer',
            'bid_units.lt' => 'Quantity must be less than or equals to '.$this->selectedCertificate->remaining_units,
            'bid_units.gt' => 'Please select atleast 1 unit to buy!',

            'amount.required_if' => 'Amount is required',
            'amount.numeric' => 'Amount must be a valid amount',
            'amount.lt' => 'Amount must be less than or equals to'.round($this->pricePerUnit * $this->bid_units),
            'amount.gt' => 'Amount must be greater than 0',

            'rate.required' => 'Rate is required',
            'rate.numeric' => 'Rate must be a valid rate',
            'rate.lt' => 'Rate must be less than or equals to '.$this->pricePerUnit,
            'rate.gt' => 'Rate must be greater than 0',
        ];
    }

    public function render()
    {
        return view('livewire.buy.buy-bid-modal');
    }
    public function mount(){
    }
    public function openBuyBidModal(SellCertificate $selectedCertificate){
        $this->selectedCertificate  =   $selectedCertificate;
        $this->total_reamining_units    =   $selectedCertificate->remaining_units;
        $this->max_limit_amount     =   round($this->selectedCertificate->price_per_unit * $this->selectedCertificate->remaining_units);
        $this->title = $this->selectedCertificate->certificate->project_type->type;
        $this->units = 0;
        $this->pricePerUnit = $this->selectedCertificate->price_per_unit;
        $this->cost = 0;
        $this->rate = $this->pricePerUnit;
        $this->amount = $this->pricePerUnit * $this->units;
        $this->bid_units = 0;
        //price calculation
        $this->price_average = $this->selectedCertificate->priceCalculation($this->selectedCertificate)->price_average;
        $this->price_difference = $this->selectedCertificate->priceCalculation($this->selectedCertificate)->price_difference;

        $this->showModal    =   true;
        $this->emit('makeDisableIdZero');
    }
    public function closeModal(){
        $this->resetValidation();
        $this->showModal    =   false;
        $this->emit('reRenderParent');
    }

    public function clearCalculation()
    {
        $this->pricePerUnit = 0;
        $this->cost = 0;
        $this->units = 0;
        $this->amount_difference = 0;
        $this->price_average = 0;
        $this->activeTab = 'buy';
    }
    public function increase()
    {
        if ($this->units <= $this->selectedCertificate->remaining_units) {
            $this->units = $this->units + 1;
        }
        $this->changeUnits();
    }
    public function decrease()
    {
        if ($this->units > 0) {
            $this->units = $this->units - 1;
        }
        $this->changeUnits();
    }
    public function changeUnits()
    {
        $this->resetValidation('units');
        $units  =   round((float)$this->units);
        if ($units >= 0 && $units <= $this->selectedCertificate->remaining_units) {
            $this->cost     =   round($this->pricePerUnit*$units);
            $this->units    =   $units;
        } else {
            if($units < 0){
                $this->addError('units', 'Please select atleast 1 unit to buy!');
            }else{
                $this->addError('units', 'Quantity must be less than or equals to '.$this->selectedCertificate->remaining_units.'!');
            }
            $this->units    =   0;
            $this->cost     =   0;
        }
    }
    public function changeTab($tab)
    {
        $this->resetValidation();
        if ($tab == 'buy')
            $this->activeTab = 'buy';
        else
            $this->activeTab = 'bid';
    }
    public function decreaseRate()
    {
        $rate   =   $this->rate-1;
        $this->rate =   $rate<1?0:$rate;
        $this->changeBidRate();
    }
    /**
     * Keep
     */
    public function increaseRate()
    {
        $this->rate++;
        $this->changeBidRate();
    }
    public function changeBidRate()
    {
        $this->resetValidation();
        $rate =   number_format((float)$this->rate, 2);
        $this->max_limit_amount =   round($rate * $this->selectedCertificate->remaining_units);
        if ($rate >= 1 && $rate <= $this->pricePerUnit) {
            $this->amount = round($rate * (int)$this->bid_units);
            $this->rate =   $rate;
        } else {
            $this->rate =   (float)$this->pricePerUnit;
            $this->addError('rate', 'Price per unit must be between $1 - $'.$this->pricePerUnit);
            $this->amount   =   0;
        }
    }
    public function decreaseBidUnit()
    {
        if($this->bid_units >0){
            $this->bid_units = $this->bid_units - 1;
        }
        $this->setBidUnit();
    }
    public function increaseBidUnit()
    {
        $this->bid_units = $this->bid_units + 1;
        $this->setBidUnit();
    }
    public function setBidUnit(){
        // $this->validate();
        $this->resetValidation();
        $bid_units    =   round((float)$this->bid_units);
        if ($bid_units >= 0 && $bid_units <= $this->selectedCertificate->remaining_units) {
            $this->amount       =   round($this->rate*$bid_units);
            $this->bid_units    =   $bid_units;
        } else {
            $this->bid_units    =   0;
            $this->amount       =   0;
            if($bid_units < 0){
                $this->addError('bid_units', 'Please select atleast 1 unit to buy!');
            }else{
                $this->addError('bid_units', 'Quantity must be less than or equals to '.$this->selectedCertificate->remaining_units.' unit');
            }
        }
    }
    public function switchBidShowData()
    {
        $this->resetValidation();
        $this->bidShowData = ($this->bidShowData == 'cost') ? 'units' : 'cost';
    }
    public function decreaseAmount()
    {
        $rate           =   (float)$this->rate;
        $this->amount   =    $rate * ($this->bid_units - 1);
        $this->setBidAmount();
    }
    public function increaseAmount()
    {
        $rate           =   (float)$this->rate;
        $this->amount   =   $rate * ($this->bid_units + 1);
        $this->setBidAmount();
    }
    public function setBidAmount(){
        $this->resetValidation('amount');
        $amount         =   (float)$this->amount;
        $rate           =   (float)$this->rate;
        $this->max_limit_amount = $maxLimitAmount =   round($rate * $this->selectedCertificate->remaining_units);
        $modulo         =   calculateModulo($amount,$rate);// custom modulo ('$this->amount % $this->rate' Direct modulo method wasn't working with decimal rate(devisor))
        if($modulo == 0 && $amount >= 0 && $amount <= $maxLimitAmount){
            $this->bid_units = round($amount / $rate);
            $this->amount   =   round($amount);
        }else{
            if($amount > $maxLimitAmount){
                $this->addError('amount', 'Amount must be less than or equals to '.$maxLimitAmount.'$');
            }else{
                $this->addError('amount', 'Amount must be in multiple of $'.$this->rate);
            }
            $this->amount       =   0;
        }
    }
    public function openStripeModal()
    {
        $this->validate();
        $this->emit('openStripeModal');
    }
    public function bid()
    {
        $this->validate();
        $this->emit('openStripeModal');
    }
    public function reRenderComponent(){
        $this->mount();
        $this->render();
    }
    public function payNow()
    {
        $buyer                  =   auth()->user();
        $this->primary_card_id  =   $buyer->creditCard->id;
        $receiver = $this->selectedCertificate->certificate->user;
        // $card = [
        //            'number' => $buyer->creditCard->card_no,
        //            'exp_month' => $buyer->creditCard->expiry_month,
        //            'exp_year' => $buyer->creditCard->expiry_year,
        //            'cvc' => $buyer->creditCard->cvv,
        //        ];

        //ToDo: enable below line when we are ready with the stripe setup
        //(new StripeHelper($sender))->useCard($receiver, $this->cost, $card);
        if ($this->activeTab == 'buy') {
            $data = Subscription::create([
                'user_id' => $buyer->id,
                'receiver_id' => $receiver->id,
                'name' => $receiver->name,
                'stripe_id' => $receiver->stripe_id . rand(), //remove
                'stripe_price' => $this->cost,
                'amount' => $this->cost,
                'quantity' => $this->units,
                'stripe_status' => 'success',
                'certificate_id' => $this->selectedCertificate->certificate->id,
                'sell_certificate_id' => $this->selectedCertificate->id,
                'ip_address' => request()->ip(),
                'card_detail_id' => $this->primary_card_id,
                'seller_bank_id' => BankDetail::where('user_id', $this->selectedCertificate->user_id)->first()->id
            ]);


            if ($this->selectedCertificate->remaining_units > 0) {
                SellCertificate::where('id', $this->selectedCertificate->id)->update([
                    'remaining_units' => $this->selectedCertificate->remaining_units - $this->units
                ]);
            }
            $message = 'Thank you for buying';
            $msg = 'A buyer buy this carbon credit: <a href="'.route('sell.show.certificate',$this->selectedCertificate->id).'">'.  $this->selectedCertificate->certificate->name. '</a>';
            $this->selectedCertificate->certificate->user->notify(new SendMessageNotification($msg));
            $activityLogMsg = 'A quantity of '.$this->units.' <b>:subject.certificate.project_type.type</b> type certificates has been Bought';
            $this->createPostCertificates($this->selectedCertificate);
            $this->sendFollowerNotify($this->selectedCertificate,($this->selectedCertificate->remaining_units - $this->units));
        }else{
            // $expirationDate = Carbon::createFromFormat('d/m/Y', $this->expiration_date)->format('Y-m-d');
            $data = Bid::create([
                'certificate_id' => $this->selectedCertificate->certificate->id,
                'sell_certificate_id' => $this->selectedCertificate->id,
                'user_id' => $buyer->id,
                'amount' => $this->amount,
                'rate' => $this->rate,
                'unit' => $this->bid_units,
                'initial_quantity' => $this->selectedCertificate->remaining_units,
                'expiration_date' => null,//$expirationDate,
                'card_detail_id' => $this->primary_card_id,
                'ip_address' => request()->ip(),
            ]);

            $details['url'] = route('offers');
            $details['title'] = "New bid is added in your negotiation list by ". $buyer->name;
            $details['body'] = 'Please go to this link and reply for negotiation';
            //TEMPORARY COMMENT
            // Mail::to($receiver->email)->send(new BidApprovalMail($details));
            $msg = 'A buyer submitted a bid for this carbon credit: <a href="'.route('sell.show.certificate',$this->selectedCertificate->id).'">'.  $this->selectedCertificate->certificate->name. '</a>';
            $this->selectedCertificate->certificate->user->notify(new SendMessageNotification($msg,'offers'));
            $message = 'Thank you for bidding';
            $activityLogMsg = 'A quantity of '.$this->bid_units.' <b>:subject.certificate.project_type.type</b> type certificates has been Bidding';
        }
        $this->emitTo('flash-component', 'flashMessage',['type' => 'success', 'msg' => $message]);
        activity()
            ->performedOn($data)
            ->causedBy(auth()->user())
            ->log($activityLogMsg);
        $this->clearCalculation();
        $this->emit('reRenderParent');
        $this->closeModal();
    }

    public function sendFollowerNotify($sellCertificate,$units)
    {
        $followers = $sellCertificate->followers()->get();
        if($units == 0){
            $message='A quantity is over for this Carbon Credit:'. $this->selectedCertificate->name;
        }else{
            $message=$units.'quantity of this Carbon Credit:'. $this->selectedCertificate->name .' is remaining';
        }
        foreach ($followers as $follower){
            $follower->user->notify(new SendMessageNotification($message));
        }
    }
    public function createPostCertificates($sellCertificate)
    {
        $certificate = Certificate::create([
            'user_id' => auth()->id(),
            'project_type_id' => data_get($sellCertificate->certificate, 'project_type_id'),
            'country_id' => data_get($sellCertificate->certificate, 'country_id'),
            'parent_id' => data_get($sellCertificate->certificate, 'id'),
            'name' => data_get($sellCertificate->certificate, 'name'),
            'quantity' => $this->units,
            'price' => round($this->cost, 2),
            'description' => data_get($sellCertificate->certificate, 'description'),
            'approving_body' => data_get($sellCertificate->certificate, 'approving_body'),
            'link_to_certificate' => data_get($sellCertificate->certificate, 'link_to_certificate'),
            'status' => 2
        ]);

        foreach ($sellCertificate->certificate->files()->get() as $file) {
            $certificate->files()->create([
                'file_path' => $file->file_path,
            ]);
        }

        SellCertificate::create([
            'certificate_id' => $certificate->id,
            'user_id' => auth()->id(),
            'units' => $this->units,
            'remaining_units' => $this->units,
            'price_per_unit' => round($this->cost/$this->units,2),
            'is_main' => true,
            'status' => 2,
        ]);
    }
}
