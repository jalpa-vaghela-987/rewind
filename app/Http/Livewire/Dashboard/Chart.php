<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Subscription;
use Carbon\Carbon;
use Livewire\Component;

class Chart extends Component
{
    public array $dataset = [];
    public array $labels = [];
    public $maxDays = 14;
    public $maxValue = 100;
    public $stepSize = 10;
    public $price,$name,$icon,$sellCertificateId,$priceDifference,$differenceType;
    public $user;

    protected $listeners = [
        'certificate-selected' => 'certificateSelected',
    ];

    public function certificateSelected($prop)
    {
        $this->maxDays = $prop[1];
        $certificate = Subscription::select('*')->with('certificate')->where('certificate_id',$prop[0])/*->where('receiver_id',$this->user->id)*/->orderBy('id','desc')->first();

        if($certificate){
            $this->price = $certificate->price;
            $this->name = $certificate->certificate->project_type->type;
            $this->sellCertificateId = $certificate->certificate->id;
            $this->icon = $certificate->certificate->project_type->image_icon;
            $this->priceDifference = $certificate->sell_certificate->priceCalculation($certificate->sell_certificate)->price_average;
        }

        $labels = $this->getLabels($this->maxDays);


        $this->emit('updateChart', [
            'data' =>  $this->getRandomData($this->maxDays),
            'labels' => $labels,
            'maxValue' => $this->maxValue,
            'stepSize' => round($this->maxValue / 10)
        ]);

    }

        public function mount()
        {
            $this->user     =   auth()->user();
            //$certificate = Certificate::where('user_id',$this->user->id)->orderBy('id','desc')->first();
            $certificate = Subscription::select('*')/*->where('receiver_id',$this->user->id)*/->orderBy('id','desc')->first();

            if($certificate){
                $this->price = $certificate->price;
                $this->name = $certificate->certificate->project_type->type;
                $this->sellCertificateId = $certificate->sell_certificate_id;
                $this->icon = $certificate->certificate->project_type->image_icon;

                $this->priceDifference = $certificate->sell_certificate->priceCalculation($certificate->sell_certificate)->price_average;
            }
            $this->labels = $this->getLabels($this->maxDays);
            $this->dataset = $this->getRandomData($this->maxDays);

            /*$this->dataset = [
                [
                    'fill'=> true,
                    'backgroundColor'=> 'gradient',
                    'borderColor'=> '#55D168',
                    'borderWidth'=> 3,
                    'pointRadius'=> 0,
                    'pointHoverRadius'=> 10,
                    'hitRadius'=> 5,
                    'label' => 'Trending',
                    'data' => $this->getRandomData(),
                ],
            ];*/
        }

        private function getLabels($days)
        {
            $labels = [];
            if($days == 14 || $days == 30) {
                $startDate = Carbon::now()->subDays($days - 1);
                $endDate = Carbon::now();
                for ($i = $startDate; $i < $endDate; $i->addDay()) {
                    $labels[] = $i->format('D');
                }
            }else if($days == 180){
                $counter = 6;
                $startDate = Carbon::now()->subMonths($counter - 1);
                $endDate = Carbon::now();
                for ($i = $startDate; $i < $endDate; $i->addMonth()) {
                    $labels[] = $i->format('M');
                }
            }else if($days == 365){
                $counter = 12;
                $startDate = Carbon::now()->subMonths($counter - 1);
                $endDate = Carbon::now();
                for ($i = $startDate; $i < $endDate; $i->addMonth()) {
                    $labels[] = $i->format('M');
                }
            }
            return $labels;
        }

    public function getRandomData($days)
    {
        $data = [];
        $maxValue = 0;
        $stepSize = 0;

        if($days == 14 || $days == 30) {
            $j = 0;
            $startDate = Carbon::now()->subDays($days - 1);
            $endDate = Carbon::now();

            $from = Carbon::now()->subDays($days - 1)->format('Y-m-d');
            $to = Carbon::now()->format('Y-m-d');

            $certificate = Subscription::select('*')->with('certificate')->where('sell_certificate_id',$this->sellCertificateId)
                ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
                ->get();
            $certificate = $certificate->groupBy('order_date');


            for ($i = $startDate; $i < $endDate; $i->addDay()) {
                $currntDate = $i->format('d/m/y');
                $value = 0;
                if(isset($certificate[$currntDate]) && $certificate[$currntDate]->count() > 0){
                    $value = $certificate[$currntDate]->sum('amount');
                }
                $data[] = ['x'=>$j,'y'=>$value,'date'=> $i->format('d/m/y')];
                $j++;
            }
        }else if($days == 180){
            $counter = 6;
            $startDate = Carbon::now()->subMonths($counter - 1);
            $endDate = Carbon::now();
            $from = Carbon::now()->subMonths($counter - 1)->format('Y-m-d');
            $to = Carbon::now()->format('Y-m-d');
            $certificate = Subscription::select('*')->with('certificate')->where('sell_certificate_id',$this->sellCertificateId)
                ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
                ->get();
            $certificate = $certificate->groupBy('order_month');
            $j = 0;
            for ($i = $startDate; $i < $endDate; $i->addMonth()) {
                $currntDate = $i->format('M');
                $value = 0;
                if(isset($certificate[$currntDate]) && $certificate[$currntDate]->count() > 0){
                    $value = $certificate[$currntDate]->sum('price');
                }
                $data[] = ['x'=>$j,'y'=>$value,'date'=> $i->format('M')];
                $j++;
            }
        }else if($days == 365){
            $counter = 12;
            $startDate = Carbon::now()->subMonths($counter - 1);
            $endDate = Carbon::now();

            $from = Carbon::now()->subMonths($counter - 1)->format('Y-m-d');
            $to = Carbon::now()->format('Y-m-d');
            $certificate = Subscription::select('*')->with('certificate')->where('sell_certificate_id',$this->sellCertificateId)
                ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
                ->get();
            $certificate = $certificate->groupBy('order_month');

            $j = 0;
            for ($i = $startDate; $i < $endDate; $i->addMonth()) {
                $currntDate = $i->format('M');
                $value = 0;
                if(isset($certificate[$currntDate]) && $certificate[$currntDate]->count() > 0){
                    $value = $certificate[$currntDate]->sum('price');
                }
                $data[] = ['x'=>$j,'y'=>$value,'date'=> $i->format('M')];
                $j++;
            }
        }

        if (!empty($data))
        {
            foreach ($data as $chart)
            {
                if($chart['y'] > $maxValue)
                {
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

    public function render()
    {
        //$pr = [1,14];
        //$this->certificateSelected($pr);
        return view('livewire.dashboard.chart');
    }
}
