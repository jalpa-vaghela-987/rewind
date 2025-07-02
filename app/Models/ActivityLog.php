<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class ActivityLog extends Model
{
    use HasFactory;
    public $timestamps  = false;
    protected $dates = ["created_at","updated_at"];
    protected $table = "activity_log";
    protected $appends  = ['dateHumanize','json_data','day'];

    public function __construct() {
        $this->userInstance = User::class;
    }

    public function getDateHumanizeAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getJsonDataAttribute()
    {
        return json_decode($this->data,true);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function getDayAttribute()
    {
        $day    = date('d',strtotime($this->created_at));
        $today  = date('d');
        if($day==$today){
            return 'Today';
        }
        if($day==($today-1)){
            return 'Yesterday';
        }
        return date('D d, M',strtotime($this->created_at));
    }
    public function getTimeAttribute()
    {
        return date('h:i A',strtotime($this->created_at));
    }
}
