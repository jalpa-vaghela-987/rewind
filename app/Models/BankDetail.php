<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{
    use HasFactory;
    /**
     * associated table
    */
    protected $table    = 'bank_details';
    protected $fillable = ['user_id','name','bic','iban','country_id','country_id','beneficiary_name','is_active','is_primary'];
    /**
     * Get the country that owns the BankDetail
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Country::class,'country_id');
    }

    public function certificate(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Certificate::class,'user_id', 'user_id');
    }
}
