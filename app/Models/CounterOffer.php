<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounterOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'bid_id',
        'user_id',
        'amount',
        'quantity',
        'parent_id',
        'status',
        'type',
        'status_update_user_id'
    ];

    public function bids(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Bid::class, 'bid_id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
