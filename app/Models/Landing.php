<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    public static function getActives(){
        $cardsByUser = Card::where('user_id', Auth::user()->id)->pluck("id");
        $lending = [];

        foreach ($cardsByUser as $card_id) {
            $card_ins = Card::find($card_id);
            $items = $card_ins->items()->whereNot('landing_id', null )
            ->leftjoin('landings', 'items.landing_id', 'landings.id')
            ->select('landings.*', 'items.*')
            ->get();
            foreach ($items as $item) {

                $type_lending = [];
                if($item['is_lending'] != 0){
                    array_push($type_lending, ['title' => 'Prestamo', 'colorSelected' => "bg-green-200", 'colorSelectedText' => "text-green-900"]);
                }
                if($item['is_fee'] != 0){
                    array_push($type_lending, ['title' => 'Cuotas', 'colorSelected' => "bg-blue-300", 'colorSelectedText' => "text-blue-900"]);
                }

                $itemOrder = [
                    'id' => $item['id'],
                    'title' => $item['title'],
                    'amount' => $item['amount'],
                    'created_at' => $item['created_at'],
                    'type_lending' => $type_lending,
                    'state' => $item['state_id'],
                    'bank' => $card_ins['name'],
                    'lending_id' => $item['landing_id']
                ];

                array_push($lending, $itemOrder);
            }
        }

        return $lending;
    }  

    public static function getSumActives(){
        $total = 0;
        $cardsByUser = Card::where('user_id', Auth::user()->id)->pluck("id");
        $lending = [];

        foreach ($cardsByUser as $card_id) {
            $card_ins = Card::find($card_id);
            $items = $card_ins->items()->whereNot('landing_id', null )
            ->leftjoin('landings', 'items.landing_id', 'landings.id')
            ->select('landings.*', 'items.*')
            ->get();
            foreach ($items as $item) {
                if($item['state_id'] != 2){
                    $total += $item['amount'];
                }

            }
        }

        return $total;
    }  

}
