<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'field',
        'street',
        'country_id',
        'registration_id',
        'incorporation_doc_url',
        'city',
        'status'
    ];

    use HasFactory;
    /**
     * Get the country associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function country(): \Illuminate\Database\Eloquent\Relations\belongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    /**
     * Get the user that owns the Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function getIncorporationDocUrlAttribute()
    {
        return asset('storage/'.$this->attributes['incorporation_doc_url']);
    }
}
