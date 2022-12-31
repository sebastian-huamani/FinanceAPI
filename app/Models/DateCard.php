<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DateCard extends Model
{
    use HasFactory;

    protected $fillable = ['billing_cycle', 'closing_date', 'payment_due_date'];

    // One To One
    public function user(){
        return $this->hasOne(User::class);
    }

}
