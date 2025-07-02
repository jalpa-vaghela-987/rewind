<?php

namespace App\Http\Livewire\Dashboard\Guest;

use App\Models\Subscription;
use Carbon\Carbon;
use Livewire\Component;

class GuestDashboardChart extends Component
{
    public array $dataset = [];
    public array $labels = [];
    public $maxDays = 14;
    public $maxValue = 100;
    public $stepSize = 10;
    public $price,$name,$icon,$certificateId,$priceDifference = 0,$differenceType;

    protected $listeners = [
        'certificate-selected' => 'certificateSelected',
    ];

    public function certificateSelected($prop)
    {
        $this->maxDays = $prop[1];
        $certificate = Subscription::select('*')->with('certificate')->where('certificate_id',$prop[0])->orderBy('id','desc')->first();
        if($certificate){
            $this->price = $certificate->price;
            $this->name = $certificate->certificate->project_type->type;
            $this->certificateId = $certificate->certificate->id;
            $this->icon = $certificate->certificate->project_type->image_icon;

            $difference = ($certificate->price * 100) /$certificate->certificate->price;
            $difference = abs(100 - $difference);
            $this->priceDifference = $difference;
            $this->differenceType = $certificate->price > $certificate->certificate->price ? 'inc' : 'dec';
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
        $certificate = Subscription::select('*')->with('certificate')->orderBy('id','desc')->first();
        if($certificate){
            $this->price = $certificate->price;
            $this->name = $certificate->certificate->project_type->type;
            $this->certificateId = $certificate->certificate->id;
            $this->icon = $certificate->certificate->project_type->image_icon;

            $difference = ($certificate->price * 100) /$certificate->certificate->price;
            $difference = abs(100 - $difference);
            $this->priceDifference = $difference;
            $this->differenceType = $certificate->price > $certificate->certificate->price ? 'inc' : 'dec';
        }
        $this->labels = $this->getLabels($this->maxDays);
        $this->dataset = $this->getRandomData($this->maxDays);

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

    private function getRandomData($days)
    {
        /*[
            { x: 0, y: 30, date: '1/1/22' },
            { x: 1, y: 18, date: '2/1/22' },
            { x: 2, y: 39, date: '3/1/22' },
            { x: 3, y: 70, date: '4/1/22' },
            { x: 4, y: 79, date: '5/1/22' },
            { x: 5, y: 65, date: '6/1/22' },
            { x: 6, y: 90, date: '7/1/22' },
            { x: 7, y: 30, date: '8/1/22' },
            { x: 8, y: 60, date: '9/1/22' },
            { x: 9, y: 60, date: '10/1/22' },
            { x: 10, y: 50, date: '11/1/22' },
            { x: 11, y: 79, date: '12/1/22' },
            { x: 12, y: 65, date: '13/1/22' },
            { x: 13, y: 90, date: '14/1/22' },
        ];*/

        $data = [];
        if($days == 14 || $days == 30) {
            $j = 0;
            $startDate = Carbon::now()->subDays($days - 1);
            $endDate = Carbon::now();

            $from = Carbon::now()->subDays($days - 1)->format('Y-m-d');
            $to = Carbon::now()->format('Y-m-d');
            $certificate = Subscription::select('*')->with('certificate')->where('certificate_id',$this->certificateId)
                ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
                ->get();
            if($certificate->max('price') && $certificate->max('price') > 0){
                $this->maxValue = round($certificate->max('price'),2);
                $this->stepSize = round($certificate->max('price') / 10);

            }
            $certificate = $certificate->groupBy('order_date');


            for ($i = $startDate; $i < $endDate; $i->addDay()) {
                $currntDate = $i->format('d/m/y');
                $value = 0;
                if(isset($certificate[$currntDate]) && $certificate[$currntDate]->count() > 0){
                    $value = $certificate[$currntDate]->avg('price');
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
            $certificate = Subscription::select('*')->with('certificate')->where('certificate_id',$this->certificateId)
                ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
                ->get();
            if($certificate->max('price') && $certificate->max('price') > 0){
                $this->maxValue = round($certificate->max('price'),2);
                $this->stepSize = round($certificate->max('price')/ 10);
            }
            $certificate = $certificate->groupBy('order_month');
            $j = 0;
            for ($i = $startDate; $i < $endDate; $i->addMonth()) {
                $currntDate = $i->format('M');
                $value = 0;
                if(isset($certificate[$currntDate]) && $certificate[$currntDate]->count() > 0){
                    $value = $certificate[$currntDate]->avg('price');
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
            $certificate = Subscription::select('*')->with('certificate')->where('certificate_id',$this->certificateId)
                ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
                ->get();
            if($certificate->max('price') && $certificate->max('price') > 0){
                $this->maxValue = round($certificate->max('price'),2);
                $this->stepSize = round($certificate->max('price') / 10);
            }
            $certificate = $certificate->groupBy('order_month');

            $j = 0;
            for ($i = $startDate; $i < $endDate; $i->addMonth()) {
                $currntDate = $i->format('M');
                $value = 0;
                if(isset($certificate[$currntDate]) && $certificate[$currntDate]->count() > 0){
                    $value = $certificate[$currntDate]->avg('price');
                }
                $data[] = ['x'=>$j,'y'=>$value,'date'=> $i->format('M')];
                $j++;
            }
        }
        return $data;
    }

    public function render()
    {
        return view('livewire.dashboard.guest.guest-dashboard-chart');
    }
}
