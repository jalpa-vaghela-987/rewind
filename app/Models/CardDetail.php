<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CardDetail extends Model
{
    use HasFactory, SoftDeletes;
    protected $table    = 'card_details';
    protected $fillable = ['user_id','card_no','card_holder_name','expiry_month','expiry_year','cvv','is_active','is_primary'];
}
