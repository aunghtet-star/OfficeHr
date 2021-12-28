<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckinCheckout extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function employee(){
       return $this->belongsTo('App\User','user_id','id');
    }
}
