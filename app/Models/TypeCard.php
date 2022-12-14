<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeCard extends Model
{
    use HasFactory;

    // One To Many
    public function cards(){
        return $this->belongsTo(Card::class);
    }
}
