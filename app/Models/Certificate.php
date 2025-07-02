<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Certificate extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'certificates';
    protected $fillable = ['user_id', 'project_type_id', 'country_id', 'parent_id', 'name', 'description', 'file_path',
        'price', 'quantity', 'approving_body', 'link_to_certificate', 'status', 'is_active','project_year','lattitude','longitude','vintage','verify_by','total_size','registry_id'];
    protected $appends = ['price_diff_percentage'];

    const STATUS_PENDING = 1;
    const STATUS_APPROVED = 2;
    const STATUS_ON_SELL = 3;
    const STATUS_DECLINED = 4;

    public function country()
    : \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function user()
    : \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function project_type()
    : \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(ProjectType::class, 'project_type_id');
    }

    public function sell_certificate()
    : \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(SellCertificate::class, 'id', 'certificate_id')->where('remaining_units', '>', 0);
    }

    public function sell_certificates()
    : \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this->hasMany(SellCertificate::class, 'certificate_id')->where('remaining_units', '>', 0);
    }

    public function followers(): \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this->hasMany(CreditFollower::class, 'sell_certificate_id', 'id');
    }
    public function last_sell_certificate()
    : \Illuminate\Database\Eloquent\Relations\hasOne
    {
        return $this->hasOne(SellCertificate::class, 'certificate_id')->orderBy('id', 'desc');
    }

    public function subscriptions()
    : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subscription::class, 'certificate_id');
    }

    public function bankDetail()
    : \Illuminate\Database\Eloquent\Relations\hasOne
    {
        return $this->hasOne(BankDetail::class, 'user_id', 'user_id')->where(['is_active' => 1, 'is_primary' => 1]);
    }

    public function files()
    : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CertificateFile::class);
    }

    public function getTotalAttribute()
    {
        return $this->quantity * $this->price;
    }

    public function getPriceDiffPercentageAttribute()
    {
        $percentDiff = 0;
        $lastSoldPrice = Subscription::select(DB::raw('(amount/quantity) as selling_price'))
            ->where('certificate_id', $this->attributes['id'])
            ->orderBy('id', 'DESC')
            ->value('selling_price');
        if ( $lastSoldPrice ) {
            $sellPercentage = ($this->attributes['price'] > 0) ? (100 / $this->attributes['price']) * $lastSoldPrice : 0;
            $percentDiff = $sellPercentage - 100;
        }
        return number_format($percentDiff, 2);
    }

    public function getAbleToCancelAttribute()
    {
        return (bool) $this->sell_certificates()->where('status', self::STATUS_ON_SELL)->count() > 1;
    }

    public function getPricePerUnitAttribute()
    {
        return price_format($this->price / $this->quantity);
    }

    public function getChart($days)
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
        if ( $days == 14 || $days == 30 ) {
            $j = 0;
            $startDate = Carbon::now()->subDays($days - 1);
            $endDate = Carbon::now();

            $from = Carbon::now()->subDays($days - 1)->format('Y-m-d');
            $to = Carbon::now()->format('Y-m-d');
            $certificate = Subscription::select('*')->with('certificate')->where('certificate_id', $this->id)
                ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                ->get();
            if ( $certificate->max('price') && $certificate->max('price') > 0 ) {
                $this->maxValue = number_format($certificate->max('price'), 2);
                $this->stepSize = round((float) $this->maxValue / 10);

            }
            $certificate = $certificate->groupBy('order_date');


            for ($i = $startDate; $i < $endDate; $i->addDay()) {
                $currentDate = $i->format('d/m/y');
                $value = 0;
                if ( isset($certificate[$currentDate]) && $certificate[$currentDate]->count() > 0 ) {
                    $value = $certificate[$currentDate]->avg('price');
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
                $certificate = Subscription::select('*')->with('certificate')->where('certificate_id', $this->id)->where('receiver_id', auth()->id())
                    ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                    ->get();
                if ( $certificate->max('price') && $certificate->max('price') > 0 ) {
                    $this->maxValue = number_format($certificate->max('price'), 2);
                    $this->stepSize = round($this->maxValue / 10);
                }
                $certificate = $certificate->groupBy('order_month');
                $j = 0;
                for ($i = $startDate; $i < $endDate; $i->addMonth()) {
                    $currentDate = $i->format('M');
                    $value = 0;
                    if ( isset($certificate[$currentDate]) && $certificate[$currentDate]->count() > 0 ) {
                        $value = $certificate[$currentDate]->avg('price');
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
                    $certificate = Subscription::select('*')->with('certificate')->where('certificate_id', $this->id)->where('receiver_id', auth()->id())
                        ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                        ->get();
                    if ( $certificate->max('price') && $certificate->max('price') > 0 ) {
                        $this->maxValue = number_format($certificate->max('price'), 2);
                        $this->stepSize = round($this->maxValue / 10);
                    }
                    $certificate = $certificate->groupBy('order_month');

                    $j = 0;
                    for ($i = $startDate; $i < $endDate; $i->addMonth()) {
                        $currentDate = $i->format('M');
                        $value = 0;
                        if ( isset($certificate[$currentDate]) && $certificate[$currentDate]->count() > 0 ) {
                            $value = $certificate[$currentDate]->avg('price');
                        }
                        $data[] = ['x' => $j, 'y' => $value, 'date' => $i->format('M')];
                        $j++;
                    }
                } else {
                    $days = 14;
                    $j = 0;
                    $startDate = Carbon::now()->subDays($days - 1);
                    $endDate = Carbon::now();

                    $from = Carbon::now()->subDays($days - 1)->format('Y-m-d');
                    $to = Carbon::now()->format('Y-m-d');
                    $certificate = Subscription::select('*')->with('certificate')->where('certificate_id', $this->id)
                        ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
                        ->get();
                    if ( $certificate->max('price') && $certificate->max('price') > 0 ) {
                        $this->maxValue = number_format($certificate->max('price'), 2);
                        $this->stepSize = round($this->maxValue / 10);

                    }
                    $certificate = $certificate->groupBy('order_date');


                    for ($i = $startDate; $i < $endDate; $i->addDay()) {
                        $currentDate = $i->format('d/m/y');
                        $value = 0;
                        if ( isset($certificate[$currentDate]) && $certificate[$currentDate]->count() > 0 ) {
                            $value = $certificate[$currentDate]->avg('price');
                        }
                        $data[] = ['x' => $j, 'y' => $value, 'date' => $i->format('d/m/y')];
                        $j++;
                    }
                }
            }
        }
        return $data;
    }

    /**Get chart data for listing
     * price diffrence data
     */
    public function getChartData($certificateId, $days)
    {
        if ( $days == '1D' ) {
            $days = 1;
        } elseif ( $days == '7D' ) {
            $days = 7;
        } elseif ( $days == '1M' ) {
            $days = 30;
        } elseif ( $days == '6M' ) {
            $days = 6;
        }
        $data = [];

        if ( $days != 6 ) {
            $startDate = Carbon::now()->subDays($days - 1);
            $endDate = Carbon::now();
            $from = Carbon::now()->subDays($days - 1)->format('Y-m-d');
            $to = Carbon::now()->format('Y-m-d');
        } else {
            $startDate = Carbon::now()->subMonths($days - 1);
            $endDate = Carbon::now();
            $from = Carbon::now()->subMonths($days - 1)->format('Y-m-d');
            $to = Carbon::now()->format('Y-m-d');
        }

        $subscription = Subscription::select('*')
            ->with('certificate')
            ->where('certificate_id', $certificateId)
            ->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59']);
        $count = $subscription->count();
        $subscription = $subscription->get();

        if ( $days != 6 ) {
            $subscription = $subscription->groupBy('order_date');
        } else {
            $subscription = $subscription->groupBy('order_month');
        }

        $j = 0;
        $priceDifference = 0;
        if ( $days != 6 ) {
            for ($i = $startDate; $i < $endDate; $i->addDay()) {
                $currentDate = $i->format('d/m/y');
                if ( isset($subscription[$currentDate]) && $subscription[$currentDate]->count() > 0 ) {
                    $total = $subscription[$currentDate]->sum('amount');
                    $priceDifference = ($total) / $count;
                }
                $data[] = ['x' => $j, 'y' => $priceDifference, 'date' => $i->format('d/m/y')];
                $j++;
            }
        } else {
            for ($i = $startDate; $i < $endDate; $i->addMonth()) {
                $currentDate = $i->format('M');
                if ( isset($subscription[$currentDate]) && $subscription[$currentDate]->count() > 0 ) {
                    $total = $subscription[$currentDate]->sum('amount');
                    $priceDifference = ($total) / $count;
                }
                $data[] = ['x' => $j, 'y' => $priceDifference, 'date' => $i->format('M')];
                $j++;
            }
        }
        return $data;
    }

    public static function statuses()
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_APPROVED,
            self::STATUS_ON_SELL,
            self::STATUS_DECLINED
        ];
    }


}
