<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['title' ,'body', 'amount', 'template_id', 'especial', 'created_at', 'updated_at'];
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

    public static function lendingByEspecialState(int $especial , int $state_id){
        $cards = Card::where('user_id', Auth::user()->id)->pluck("id");

        if( !$cards){
            return -1;
        }

        $items = [];

        foreach ($cards as $card) {
            $card_ins = Card::where('id', $card)->first();
            foreach ($card_ins->items as $item) {
                if($item->especial == $especial && $item->ByState($state_id) ){
                    array_push($items, $item);
                }
            }
        }

        return $items;
    }

    
}