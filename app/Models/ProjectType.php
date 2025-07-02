<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectType extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'abbreviation', 'is_active','image'];
    protected $appends = [
        'image_icon'
    ];
    public function getImageIconAttribute(){
        $type = $this->attributes['type'];
        $type = str_replace('Depleting','Delpleting',$type);
        if($type == 'Forest- ARB'){
            $type = 'Forest-ERB';
        }elseif ($type == 'Livestock-ARB'){
            $type = 'Livestock-ERB';
        }

        $icon = str_replace(' ','-',$type);
        $icon = str_replace('--','-',$icon);

         return 'icon-'.$icon;
    }

}
