<?php

namespace App\Http\Livewire\Buy;

use App\Models\BuyPriceAlert;
use App\Models\SellCertificate;
use Livewire\Component;

class PriceAlertModal extends Component
{
    protected $listeners = ['openPriceAlertModal'];
    public $showModal  =   false;
    public $title,$units,$pricePerUnit,$cost,$rate,$amount,$alert_price=0;
    public $alert_percentage = 0;
    public $priceShowData = 'price';
    public $selectedCertificate;
    public function rules(){
        $rule   =   [
            'alert_price' => 'required|numeric|gt:0',
            'alert_percentage'=>'required|numeric'
        ];
        return $rule;
    }

    public function messages(){
        return [
            'alert_price.required'=>'Alert price is required',
            'alert_price.numeric'=>'Alert price must be a valid price',
            'alert_price.gt'=>'Alert price must be greater than 0',
            'alert_percentage.required'=>'Alert percentage is required',
            'alert_percentage.numeric'=>'Alert percentage must be a valid price',
        ];
    }
    public function render()
    {
        $this->resetValidation();
        return view('livewire.buy.price-alert-modal');
    }
    public function openPriceAlertModal(SellCertificate $sellCertificate){
        $this->selectedCertificate  =   $sellCertificate;
        $this->title                =   $this->selectedCertificate->certificate->project_type->type;
        $this->units                =   $this->selectedCertificate->remaining_units;
        $this->pricePerUnit         =   $this->selectedCertificate->price_per_unit;
        $this->cost                 =   $this->pricePerUnit * $this->units;
        $this->rate                 =   $this->pricePerUnit;
        $this->amount               =   $this->pricePerUnit * $this->units;
//        $priceAlert= BuyPriceAlert::where(['certificate_id'=>$certificate->id,   'user_id' => auth()->id()])->first();
        $this->alert_price          =   $this->pricePerUnit;
//        $this->alert_percentage =  $priceAlert ?  $priceAlert->percentage: 0;
        $this->showModal    =   true;
    }
    public function closeModal(){
        $this->resetData();
        $this->showModal=false;
    }
    public function resetData(){
        $this->units=0;
        $this->pricePerUnit=0;
        $this->cost=0;
        $this->rate=0;
        $this->amount=0;
        $this->alert_price=0;
        $this->alert_percentage = 0;
        $this->priceShowData = 'price';
    }
    public function increasePrice($type)
    {
        $price  =   (float)$this->$type += 1;
        if ($type == 'alert_price') {
            $percentage = ($price * 100) / $this->pricePerUnit;
            $this->alert_percentage = round($percentage, 2)-100;
        } else {
            $percentage = ((float)$this->$type * $this->pricePerUnit) / 100;
            $price = $this->pricePerUnit + $percentage;
            $this->alert_price = round($price, 2);
        }
    }

    public function decreasePrice($type)
    {
        $price =    (float)$this->$type -= 1;
        if ($type == 'alert_price') {
            $percentage = ($price * 100) / (float)$this->pricePerUnit;
            $this->alert_percentage = round($percentage, 2)-100;
        } else {
            $percentage = (float)($this->$type * $this->pricePerUnit) / 100;
            $price = $this->pricePerUnit + $percentage;
            $this->alert_price = round($price, 2);
        }
    }
    public function priceChange($value)
    {
        // FORMULA: % = ((100 * NEW PRICE)/PRICE PER UNIT)-100
        $price = (float)$value;
        $percentage = ($price * 100) / $this->pricePerUnit;
        $this->alert_percentage = round($percentage, 2)-100; //to calculate increase/decrease in percent
    }
    public function switchPriceShowData()
    {
        $this->priceShowData = ($this->priceShowData == 'price') ? 'percentage' : 'price';
    }
    public function setAlert()
    {
        $this->validate();
        $user_id   =   auth()->id();
        $sellAlert = BuyPriceAlert::updateOrCreate(
            ['sell_certificate_id' => $this->selectedCertificate->id,
                'user_id' => $user_id
            ],
            [
                'sell_certificate_id' => $this->selectedCertificate->id,
                'certificate_id' => $this->selectedCertificate->certificate->id,
                'user_id' => $user_id,
                'amount' => $this->alert_price,
                'percentage' => $this->alert_percentage
            ]);
            $message    =   "Price alert for <b>".$this->title."</b> certificate at <b>".$this->priceShowData.": ".($this->priceShowData=='price'?$this->alert_price.'$':$this->alert_percentage.'%').'</b> created successfully';
            $this->emitTo('flash-component', 'flashMessage',['type' => 'success', 'msg' => $message]);
            activity()
            ->performedOn($sellAlert)
            ->causedBy(auth()->user())
            ->log($message);
        $this->closeModal();
    }
    public function percentageChange($value)
    {
        // FORMULA: price = PRICE PER UNIT+((PRIC PER UNIT * PERCENTAGE)/100)
        $percentage_price = ((float)$value * $this->pricePerUnit) / 100;
        $price      = $this->pricePerUnit + $percentage_price;
        $this->alert_price = round($price, 2);
    }
}
