<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;
    protected $table = "bids";
    protected $fillable = ['certificate_id','sell_certificate_id','user_id','amount','status', 'rate', 'unit', 'expiration_date', 'card_detail_id', 'initial_quantity','ip_address'];

    public function certificate(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Certificate::class, 'id','certificate_id');
    }
    public function sell_certificate(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(SellCertificate::class, 'id','sell_certificate_id',);
    }
    public function user(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function counterOffer(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CounterOffer::class, 'bid_id','id')->with('user')->orderBy('parent_id', 'asc');
    }
}
