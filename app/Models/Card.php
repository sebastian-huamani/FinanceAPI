<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'bottom_line', 'amount', 'name_banck', 'card_expiration_date', 'type_card_id', 'date_card_id', 'state_id', 'user_id', 'created_at', 'updated_at', 'color_id'];

    // One To Many (Inverse) / Belongs To
    public function state(){
        return $this->belongsTo(state::class);
    }

    // One To Many (Inverse) / Belongs To
    public function type_card(){
        return $this->belongsTo(TypeCard::class);
    }

    // One To Many (Inverse) / Belongs To
    public function user(){
        return $this->belongsTo(User::class);
    }

    //Many To Many (Polymorphic) inverse
    public function items(){
        return $this->morphedByMany('App\Models\Item', 'transactionable');
    }

    // One To Many
    public function date_card(){
        return $this->belongsTo(DateCard::class);
    }

    public function color()
    {
        return $this->belongsTo(Card::class);
    }
}
