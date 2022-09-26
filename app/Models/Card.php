<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'bottom_line', 'name_banck', 'card_expiration_date', 'type_cards_id'];
}
