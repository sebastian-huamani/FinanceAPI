<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataInfoUser extends Model
{
    use HasFactory;
    protected $fillable = ['full_credit', 'aviable_credit', 'full_debit', 'aviable_debit', 'user_id', 'created_at', 'updated_at'];
    protected $casts = ['full_credit' => 'array', 'aviable_credit' => 'array', 'full_debit' => 'array', 'aviable_debit' => 'array'];


    public function user(){
        return $this->belongsTo(DataInfoUser::class);
    }
}
