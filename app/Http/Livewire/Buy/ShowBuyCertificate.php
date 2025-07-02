<?php

namespace App\Http\Livewire\Buy;

use App\Mail\BidApprovalMail;
use App\Models\BankDetail;
use App\Models\Bid;
use App\Models\CardDetail;
use App\Models\Certificate;
use App\Models\SellCertificate;
use App\Models\Subscription;
use App\Services\StripeHelper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class ShowBuyCertificate extends Component
{
    protected $listeners = ['openModal' => 'openModal', 'certificate-selected' => 'certificateSelected', 'toggleDateModal' => 'toggleDateModal', 'setSelectedDate' => 'setSelectedDate'];
    public $openBuyModal = false;
    public $showStripeModal = false, $showCreditCardModal = false;
    public $showDateModal = false;
    public $showData = 'cost';
    public $bidShowData = 'cost';
    public $activeTab = 'buy';
    public $selectedCertificate = null;
    public $cardHolderName = null;
    public $paymentMethod = null;
    public $title = null;
    public $pricePerUnit = 0;
    public $cost = 0;
    public $units = 0;
    public $bid_units = 0;
    public $rate = 0;
    public $amount = 0;
    public $showModal = false;
    public $expiry;
    public $card_no;
    public $card_holder_name;
    public $expiry_month;
    public $expiry_year;
    public $cvv;
    public $card, $cards, $primary_card_id;
    public $expiration_date;
    public $price_difference = 0;
    public $amount_difference = 0;
    public $price_average = 0;
    public $maxValue = 100;
    public $stepSize = 10;
    public array $dataset = [];
    public array $labels = [];
    public $maxDays = 14;
    public $afterYear;
    public $price, $name, $icon, $certificateId, $maxPurchase = 0, $sellCertificate = null;
    public $currentImageIndex = 0;
    public $total_files = 0;

    public function rules()
    {
        $expiry_month = date('m');
        $expiry_year = date('Y');
        $this->afterYear = date("Y") - 1; //date ("Y", strtotime ("-1 year", strtotime (date('Y'))))
        if ( isset($this->expiry_year) ) {
            $expiry_year = $this->expiry_year;
        }
        if ( isset($this->expiry_month) ) {
            $expiry_month = $this->expiry_month;
        }
        $this->expiry = $expiry_month . '/31/' . $expiry_year;
        return [
            'card_no'          => ['required', 'unique:card_details,card_no,NULL,id,user_id,' . auth()->user()->id, 'digits:16'],
            'card_holder_name' => 'nullable',
            'expiry_month'     => ['required', 'numeric', 'min:01', 'max:12'],
            'expiry_year'      => ['required', 'numeric', 'date_format:Y', 'max:2099', 'after:' . $this->afterYear],
            'cvv'              => ['required', 'digits:3'],
            'expiry'           => 'after:' . date('m/d/Y'),
        ];
    }

    public function messages()
    {
        return [
            'expiry_month.integer'    => 'Month should be valid month.',
            'expiry_month.min'        => 'Month should be valid month.',
            'expiry_month.max'        => 'Month should be valid month.',
            'expiry_year.integer'     => 'Year must be a valid year',
            'expiry_year.date_format' => 'Year must in format YYYY',
            'expiry_year.after'       => 'Year must be after ' . $this->afterYear,
            'expiry_year.max'         => 'Year must be between ' . date('Y') . '-2099',
            'expiry.after'            => 'Expiry month/year is invalid',
        ];
    }

    public function render()
    {
        $user = auth()->user();
        $intent = (new StripeHelper($user))->createStripeSetupIntent();
        $currentImage = $this->currentImage();
        $total_files = $this->total_files;

        return view('livewire.buy.show-buy-certificate', compact('intent', 'currentImage', 'total_files'));
    }

    public function mount($id)
    {
        $this->resetValidation();
        $this->sellCertificate = SellCertificate::where('id', $id)->first();
        $this->price_average = $this->sellCertificate->priceCalculation($this->sellCertificate)->price_average;

        $user = auth()->user();
        $this->cards = auth()->user()->creditCards;
        $this->primary_card_id = auth()->user()->creditCard ? auth()->user()->creditCard->id : 0;
        //---------------------------------------------//
//        $data = $this->getRandomData($this->maxDays, $id);
//        $this->dataset = $data[0];
//        $this->labels = $data[1];
//        $this->maxPurchase = ($data[2] != 0 ) ? ceil($data[2]) + 1000  : 100;
//        $this->maxPurchase = 10000;

        $this->user = $user;
        $sellCertificate = $this->sellCertificate;

        if ( $sellCertificate ) {
            $this->price = $sellCertificate->certificate->price;
            $this->name = $sellCertificate->certificate->project_type->type;
            $this->certificateId = $sellCertificate->id;
            $this->icon = $sellCertificate->certificate->project_type->image_icon;
        }

        $this->labels = $this->getLabels($this->maxDays);
        $this->dataset = $this->getRandomData($this->maxDays);

    }

    public function openModal(SellCertificate $sellCertificate)
    {
        $sender = auth()->user();
        if ( !$sender->phone_verified && $sender->phone ) {
            $this->emit('openCloseResendVerificationSMS');
        } else {
            $this->selectedCertificate = $sellCertificate;
            $this->emit('openBuyBidModal', $this->selectedCertificate->id);

            //price calculation
            $this->price_average = $sellCertificate->priceCalculation($sellCertificate)->price_average;
            $this->price_difference = $sellCertificate->priceCalculation($sellCertificate)->price_difference;
        }
    }

    public function toggleDateModal()
    {
        $this->openBuyModal = !$this->openBuyModal;
        $this->showDateModal = !$this->showDateModal;
    }

    public function switchShowData()
    {
        $this->showData = ($this->showData == 'cost') ? 'units' : 'cost';
    }

    public function switchBidShowData()
    {
        $this->bidShowData = ($this->bidShowData == 'cost') ? 'units' : 'cost';
    }

    public function closeModal()
    {
        $this->openBuyModal = false;
        $this->showStripeModal = false;
        $this->showDateModal = false;
        $this->showCreditCardModal = false;
        $this->clear();
    }

    public function increase()
    {
        if ( $this->units < $this->selectedCertificate->remaining_units ) {
            $this->cost = $this->cost + $this->pricePerUnit;
            $this->units = $this->units + 1;
        } else {
            $this->addError('error', 'You can buy maximum ' . $this->selectedCertificate->remaining_units . '  units!');
        }
    }

    public function decrease()
    {
        if ( $this->units > 1 ) {
            $this->cost = $this->cost - $this->pricePerUnit;
            $this->units = $this->units - 1;
        } else {
            $this->addError('error', 'Please select atleast 1 unit to buy!');
        }
    }

    public function decreaseRate()
    {
        if ( $this->rate > 1 ) {
            $this->rate--;
            $this->amount = $this->rate * $this->bid_units;
        } else {
            $this->addError('error', 'Minimum price per unit is $1');
        }
    }

    public function increaseRate()
    {
        if ( $this->rate < $this->pricePerUnit ) {
            $this->rate++;
            $this->amount = $this->rate * $this->bid_units;
        } else {
            $this->addError('error', 'Maximum price per unit is $' . $this->pricePerUnit);
        }
    }

    public function decreaseAmount()
    {
        if ( $this->bid_units > 1 ) {
            $this->amount = $this->amount - $this->rate;
            $this->bid_units = $this->bid_units - 1;
        } else {
            $this->addError('error', 'Please select atleast 1 unit to buy');
        }
    }

    public function increaseAmount()
    {
        if ( $this->bid_units < $this->selectedCertificate->sell_certificate->remaining_units ) {
            $this->amount = $this->amount + $this->rate;
            $this->bid_units = $this->bid_units + 1;
        } else {
            $this->addError('error', 'You can buy maximum ' . $this->selectedCertificate->remaining_units . '  units!');
        }
    }

    public function changeTab($tab)
    {
        if ( $tab == 'buy' ) {
            $this->activeTab = 'buy';
        } else {
            $this->activeTab = 'bid';
        }
    }

    public function save()
    {
        if ( $this->activeTab == 'bid' ) {
            $this->validate([
                'expiration_date' => 'required',
            ]);
        }
        $this->showStripeModal = true;
        $this->openBuyModal = false;
    }

    public function clear()
    {
        $this->pricePerUnit = 0;
        $this->cost = 0;
        $this->units = 0;
        $this->expiry = null;
        $this->card_no = null;
        $this->card_holder_name = null;
        $this->expiry_month = null;
        $this->expiry_year = null;
        $this->cvv = null;
        $this->card = null;
        $this->amount = 0;
        $this->rate = 0;
        $this->bid_units = 0;
        $this->expiration_date = null;
        $this->price_difference = 0;
        $this->amount_difference = 0;
        $this->price_average = 0;
    }

    public function clearCalculation()
    {
        $this->pricePerUnit = 0;
        $this->cost = 0;
        $this->units = 0;
        $this->price_difference = 0;
        $this->amount_difference = 0;
        $this->price_average = 0;
    }

    public function payNow()
    {
        $buyer = auth()->user();
        $this->validate([
            'primary_card_id' => 'required|integer|exists:card_details,id',
        ], [
            'primary_card_id.required' => 'Please select credit card',
            'primary_card_id.integer'  => 'Cred card ID must be an integer',
            'primary_card_id.exists'   => 'Selected credit card is invalid',
        ]);
        $receiver = $this->selectedCertificate->certificate->user;
        if ( $this->primary_card_id != auth()->user()->creditCard->id ) {
            $cardDetail = CardDetail::firstWhere(['user_id' => $buyer->id, 'id' => $this->primary_card_id]);
            if ( $cardDetail ) {
                $removePrimary = CardDetail::where('user_id', $buyer->id)->update([
                    'is_primary' => 0,
                ]);
                $cardDetail->is_primary = true;
                $cardDetail->save();
            } else {
                $message = 'Invalid Card!';
                $this->closeModal();
                $this->clear();
                $this->emitTo('flash-component', 'flashMessage', ['type' => 'error', 'msg' => $message]);
            }
        } else {
            //        $card = [
            //            'number' => $this->cardNumber,
            //            'exp_month' => $this->expMonth,
            //            'exp_year' => $this->expYear,
            //            'cvc' => $this->cvv,
            //        ];

            //ToDo: enable below line when we are ready with the stripe setup
            //(new StripeHelper($sender))->useCard($receiver, $this->cost, $card);

            $sellCertificate = $this->selectedCertificate;
            if ( $this->activeTab == 'buy' ) {
                $data = Subscription::create([
                    'user_id'             => $buyer->id,
                    'receiver_id'         => $receiver->id,
                    'name'                => $receiver->name,
                    'stripe_id'           => $receiver->stripe_id . rand(), //remove
                    'stripe_price'        => $this->cost,
                    'amount'              => $this->cost,
                    'quantity'            => $this->units,
                    'stripe_status'       => 'success',
                    'certificate_id'      => $this->selectedCertificate->certificate->id,
                    'sell_certificate_id' => $this->selectedCertificate->id,
                    'card_detail_id'      => $this->primary_card_id,
                    'seller_bank_id'      => BankDetail::where('user_id', $this->selectedCertificate->user_id)->first()->id,
                ]);

                if ( $sellCertificate->remaining_units > 0 ) {
                    SellCertificate::where('id', $sellCertificate->id)->update([
                        'remaining_units' => $sellCertificate->remaining_units - $this->units,
                    ]);
                }
                $message = 'Thank you for buying';
                $activityLogMsg = 'A quantity of ' . $this->units . ' <b>:subject.certificate.project_type.type</b> type certificates has been Bought';
            } else {
                $expirationDate = Carbon::createFromFormat('d/m/Y', $this->expiration_date)->format('Y-m-d');
                $data = Bid::create([
                    'certificate_id'      => $this->selectedCertificate->certificate->id,
                    'sell_certificate_id' => $this->selectedCertificate->id,
                    'user_id'             => $buyer->id,
                    'amount'              => $this->amount,
                    'rate'                => $this->rate,
                    'unit'                => $this->bid_units,
                    'initial_quantity'    => $this->selectedCertificate->remaining_units,
                    'expiration_date'     => $expirationDate,
                    'card_detail_id'      => $this->primary_card_id,
                ]);

                $details['url'] = route('offers');
                $details['title'] = "New bid is added in your negotiation list by " . $buyer->name;
                $details['body'] = 'Please go to this link and reply for negotiation';
                Mail::to($receiver->email)->send(new BidApprovalMail($details));
                $message = 'Thank you for bidding';
                $activityLogMsg = 'A quantity of ' . $this->bid_units . ' <b>:subject.certificate.project_type.type</b> type certificates has been Bidding';
            }

            $this->closeModal();
            $this->clear();
            $this->emitTo('flash-component', 'flashMessage', ['type' => 'success', 'msg' => $message]);
            activity()
                ->performedOn($data)
                ->causedBy(auth()->user())
                ->log($activityLogMsg);
            $this->render();

            $this->price_average = $sellCertificate->priceCalculation($this->sellCertificate)->price_average;
            $sellCertificateCheck = SellCertificate::firstWhere('id', $sellCertificate->id);
            if ( $sellCertificateCheck->remaining_units > 0 ) {
                return redirect()->back();
            } else {
                return redirect()->route('buy');
            }
        }
    }

    public function certificateSelected($prop)
    {
        $this->maxDays = $prop[1];

        $sellCertificate = SellCertificate::find($prop[0]);
        $this->price = $sellCertificate->price;
        $this->name = $sellCertificate->certificate->project_type->type;
        $this->certificateId = $sellCertificate->id;
        $this->icon = $sellCertificate->certificate->project_type->image_icon;
        $labels = $this->getLabels($this->maxDays);
        $this->price_average = $sellCertificate->priceCalculation($this->sellCertificate)->price_average;

        $this->emit('updateChart', [
            'data'     => $this->getRandomData($this->maxDays),
            'labels'   => $labels,
            'maxValue' => $this->maxValue,
            'stepSize' => round($this->maxValue / 10),
        ]);
    }

    private function getLabels($days)
    {
        $labels = [];
        if ( $days == 14 || $days == 30 ) {
            $startDate = Carbon::now()->subDays($days - 1);
            $endDate = Carbon::now();
            for ($i = $startDate; $i < $endDate; $i->addDay()) {
                $labels[] = $i->format('D');
            }
        } else {
            if ( $days == 180 ) {
                $counter = 6;
                $startDate = Carbon::now()->subMonths($counter - 1);
                $endDate = Carbon::now();
                for ($i = $startDate; $i < $endDate; $i->addMonth()) {
                    $labels[] = $i->format('M');
                }
            } else {
                if ( $days == 365 ) {
                    $counter = 12;
                    $startDate = Carbon::now()->subMonths($counter - 1);
                    $endDate = Carbon::now();
                    for ($i = $startDate; $i < $endDate; $i->addMonth()) {
                        $labels[] = $i->format('M');
                    }
                }
            }
        }
        return $labels;
    }

    public function getRandomData($days)
    {
        $data = [];
        $maxValue = 100;
        $stepSize = 10;

        if ( $days == 14 || $days == 30 ) {
            $j = 0;
            $startDate = Carbon::now()->subDays($days - 1);
            $endDate = Carbon::now();

            $from = Carbon::now()->subDays($days - 1)->format('Y-m-d');
            $to = Carbon::now()->format('Y-m-d');

            $certificate = Subscription::select('*')->with('certificate')->where('sell_certificate_id', $this->certificateId)
                ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->get();
            $certificate = $certificate->groupBy('order_date');


            for ($i = $startDate; $i < $endDate; $i->addDay()) {
                $currntDate = $i->format('d/m/y');
                $value = 0;
                if ( isset($certificate[$currntDate]) && $certificate[$currntDate]->count() > 0 ) {
                    $value = $certificate[$currntDate]->sum('amount');
                }
                $data[] = ['x' => $j, 'y' => $value, 'date' => $i->format('d/m/y')];
                $j++;
            }
        } else {
            if ( $days == 180 ) {
                $counter = 6;
                $startDate = Carbon::now()->subMonths($counter - 1);
                $endDate = Carbon::now();
                $from = Carbon::now()->subMonths($counter - 1)->format('Y-m-d');
                $to = Carbon::now()->format('Y-m-d');
                $certificate = Subscription::select('*')->with('certificate')->where('sell_certificate_id', $this->certificateId)
                    ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                    ->get();
                $certificate = $certificate->groupBy('order_month');
                $j = 0;
                for ($i = $startDate; $i < $endDate; $i->addMonth()) {
                    $currntDate = $i->format('M');
                    $value = 0;
                    if ( isset($certificate[$currntDate]) && $certificate[$currntDate]->count() > 0 ) {
                        $value = $certificate[$currntDate]->sum('price');
                    }
                    $data[] = ['x' => $j, 'y' => $value, 'date' => $i->format('M')];
                    $j++;
                }
            } else {
                if ( $days == 365 ) {
                    $counter = 12;
                    $startDate = Carbon::now()->subMonths($counter - 1);
                    $endDate = Carbon::now();

                    $from = Carbon::now()->subMonths($counter - 1)->format('Y-m-d');
                    $to = Carbon::now()->format('Y-m-d');
                    $certificate = Subscription::select('*')->with('certificate')->where('sell_certificate_id', $this->certificateId)
                        ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                        ->get();
                    $certificate = $certificate->groupBy('order_month');

                    $j = 0;
                    for ($i = $startDate; $i < $endDate; $i->addMonth()) {
                        $currntDate = $i->format('M');
                        $value = 0;
                        if ( isset($certificate[$currntDate]) && $certificate[$currntDate]->count() > 0 ) {
                            $value = $certificate[$currntDate]->sum('price');
                        }
                        $data[] = ['x' => $j, 'y' => $value, 'date' => $i->format('M')];
                        $j++;
                    }
                }
            }
        }

        if ( !empty($data) ) {
            foreach ($data as $chart) {
                if ( $chart['y'] > $maxValue ) {
                    $maxValue = $chart['y'];
                }
            }
            $maxValue = $maxValue * 2;
            $stepSize = round($maxValue / 10);
        }
        $this->maxValue = $maxValue;
        $this->stepSize = $stepSize;

        return $data;
    }

    public function setSelectedDate($date)
    {
        $this->expiration_date = $date;
        $this->toggleDateModal();
    }

    /**
     * @author Moh Ashraf
     */
    public function showAddCreditCardmodal()
    {
        $this->showCreditCardModal = true;
    }

    /**
     * @author Moh Ashraf
     */
    public function saveNewCard()
    {
        $this->validate();
        $buyer = auth()->user();
        $cardDetails = [
            'user_id'          => $buyer->id,
            'card_no'          => $this->card_no,
            'card_holder_name' => $this->card_holder_name,
            'expiry_month'     => $this->expiry_month,
            'expiry_year'      => $this->expiry_year,
            'cvv'              => $this->cvv,
            'is_active'        => 1,
            'is_primary'       => 1,
        ];
        CardDetail::where('user_id', $buyer->id)->update([
            'is_primary' => 0,
        ]);
        $card = CardDetail::create($cardDetails);
        $this->primary_card_id = $card->id;
        $type = 'success';
        $msg = 'Card Added for payment!';
        $this->emitTo('flash-component', 'flashMessage', ['type' => $type, 'msg' => $msg]);
        $this->mount($this->sellCertificate->id);
        $this->showCreditCardModal = false;
    }

    public function currentImage()
    {
        $files = collect($this->sellCertificate->certificate->files()->pluck('file_path')->toArray())->map(function ($item) {
            return Storage::url($item);
        })->filter()->toArray();

        $this->total_files = count($files);

        return isset($files[$this->currentImageIndex]) ? $files[$this->currentImageIndex] : null;
    }

    public function prevImage()
    {
        if ( $this->currentImageIndex > 0 ) {
            $this->currentImageIndex--;
        }
    }

    public function nextImage()
    {
        if ( $this->currentImageIndex < $this->total_files - 1 ) {
            $this->currentImageIndex++;
        }
    }
}
