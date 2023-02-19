<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Landing extends Model
{
    use HasFactory;

    protected $fillable = ['amount', 'created_date_lending', 'payment_date_lending', 'user_id', 'state_id', 'debtor', 'postpone', 'created_at', 'updated_at'];
    protected $casts = [ 'postpone' => 'array' ];

    public function cards(){
        return $this->morphToMany('App\Models\Card', 'transactionable');
    }

    public function users(){
        return $this->hasMany(User::class);
    }
}
