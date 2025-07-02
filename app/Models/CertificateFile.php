<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateFile extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $appends = ['label'];

    public function getLabelAttribute()
    {
        if ( $this->attributes['file_path'] != '' ) {
            return asset('storage/' . $this->attributes['file_path']);
        } else {
            return null;
        }
    }
}
