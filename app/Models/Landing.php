<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Landing extends Model
{
    use HasFactory;

    protected $fillable = ['amount', 'payment_date_lending', 'state_id', 'debtor', 'is_lending', 'is_fee', 'history_quota' , 'created_at', 'updated_at'];
    protected $casts = ["history_quota" => "array"];

    public function cards()
    {
        return $this->morphToMany('App\Models\Card', 'transactionable');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function item(){
        return $this->belongsTo(Item::class);
    }

}
