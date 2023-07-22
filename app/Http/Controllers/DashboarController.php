<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboarController extends Controller
{
    public function general($date){
        $date = explode("-" ,$date) ;
        $user = User::find(Auth::user()->id);
        $cards = Card::where('user_id', $user->id)->where('state_id', 1)->pluck("id");

        $listItems = [];
        foreach ($cards as $card) {
            $item_temp = $card->items()
                ->whereYear('items.created_at', $date[0])
                ->whereMonth('items.created_at', $date[1])
                ->orderby('items.created_at', 'desc')
                ->pluck("created_at", "amount");
            
            array_push($listItems, $item_temp);
        }
        return $listItems;
    }
}
