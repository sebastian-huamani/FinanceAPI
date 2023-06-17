<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['title' ,'body', 'amount', 'template_id', 'landing_id', 'created_at', 'updated_at'];
    protected $casts = ['body' => 'array'];

    //Many To Many (Polymorphic)
    public function cards(){
        return $this->morphToMany('App\Models\Card', 'transactionable');
    }

    public function template(){
        return $this->belongsTo(Template::class);
    }

    public function landings() {
        return $this->hasMany(Landing::class);
    }

    public function scopeByState($query, int $state_id) {
        $query->join('landings', 'items.id', 'landings.item_id')->where('landings.state_id', $state_id)->get();
    }

    public static function lendingByEspecialState( int $state_id){
        $cards = Card::where('user_id', Auth::user()->id)->pluck("id");

        if( !$cards){
            return -1;
        }

        $items = [];

        foreach ($cards as $card) {
            $card_ins = Card::where('id', $card)->first();
            $items = $card_ins->items()->where('items.especial', 1)->get();
            foreach ($items as $item) {
                if($item->especial == 1 && $item->ByState($state_id) ){
                    array_push($items, $item);
                }
            }
        }

        return $items;
    }

    
}