<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = [
        'price','order_date','order_month'
    ];

    public function certificate(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Certificate::class, 'certificate_id');
    }

    public function sell_certificate(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SellCertificate::class, 'sell_certificate_id');
    }

    public function getPriceAttribute(){
        $quantity = $this->attributes['quantity'];
        $amount = $this->attributes['amount'];
        if($quantity !=0){
            return $amount/$quantity;
        }else{
            return $amount;
        }

    }

    public function seller(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function buyer(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getOrderDateAttribute(){
        $date = $this->attributes['created_at'];
        return Carbon::parse($date)->format('d/m/y');
    }
    public function getOrderMonthAttribute(){
        $date = $this->attributes['created_at'];
        return Carbon::parse($date)->format('M');
    }
    public function card_detail(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(CardDetail::class, 'card_detail_id');
    }
    public function seller_bank_detail(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(BankDetail::class, 'seller_bank_id');
    }

    public function buyer_bank_detail(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(BankDetail::class, 'user_id');
    }
}
