<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'body', 'state','user_id', 'created_at'];
    protected $casts = ['body' => 'array'];

    // One To Many (Inverse) / Belongs To
    public function user(){
        return $this->belongsTo(User::class);
    }

    // One To Many
    public function items(){
        return $this->hasMany(Item::class);
    }

}
