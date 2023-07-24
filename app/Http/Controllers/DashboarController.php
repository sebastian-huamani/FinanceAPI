<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Landing;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboarController extends Controller
{
    public function general($date)
    {
        $date = explode("-", $date);
        $user = User::find(Auth::user()->id);
        $cards = Card::where('user_id', $user->id)->where('state_id', 1)->pluck("id");

        $listItems = array();
        foreach ($cards as $card) {
            $card_temp = Card::find($card);
            $items_temp = $card_temp->items()
                ->whereYear('items.created_at', $date[0])
                ->whereMonth('items.created_at', $date[1])
                ->orderby('items.created_at', 'asc')
                ->selectRaw("date_format(items.created_at, '%Y-%m-%d') as fecha, items.amount as mount")->get();

            $data = $items_temp->mapToGroups(function ($item, $key) {
                return [$item['fecha'] => $item['mount']];
            })->toArray();

            $processItems = [];
            $sumTotal = 0;
            foreach ($data as $key => $value) {
                $sumTotal += round(array_sum($value), 2);
                $item = array('x' => $key, 'y' => round($sumTotal, 2));
                array_push($processItems, $item);
            }
            array_push($listItems, array('name' => $card_temp->name, 'data' => $processItems));
            // array_push($listItems,$data);
        }

        return response()->json([
            'res' => true,
            'msg' => $listItems
        ]);
    }

    public function dataxMonthTemplate($date, $type_card){
        $date = explode("-", $date);

        if($type_card == 3){
            $lendings = Landing::getLendingsByState([1,3])->pluck('amount', 'debtor')->get();
            
            return response()->json([
                'res' => true,
                'msg' => $lendings
            ]);
        }

        $user = User::find(Auth::user()->id);
        $cards = Card::where('user_id', $user->id)->where('state_id', 1)->where('type_card_id', $type_card)->pluck("id");

        $totalAmount = 0;
        $currentAmount = 0;

        if($type_card == 1){
            $dataLandings = Landing::getSumActives();
            $totalLendings = round($dataLandings, 2);
            $totalAmount = $user->cards()->where('cards.type_card_id', 1)->sum("amount");
            $currentAmount = round($totalAmount, 2) - round($totalLendings, 2);
        } 
 
        if($type_card == 2){
            $totalAmount = $user->cards()->where('cards.type_card_id', 2)->sum("bottom_line");
            $currentAmount = $user->cards()->where('cards.type_card_id', 2)->sum("amount");
        }

        $series = [];
        $labels =  [];
        $listItems = array();
        foreach ($cards as $card) {
            $card_temp = Card::find($card);
            $items_temp = $card_temp->items()
                ->whereYear('items.created_at', $date[0])
                ->whereMonth('items.created_at', $date[1])
                ->orderby('items.created_at', 'asc')
                ->join('templates', 'items.template_id', 'templates.id')
                ->selectRaw("items.amount as mount, items.template_id as template, templates.title as title")->get();

            $data = $items_temp->mapToGroups(function ($item, $key) {
                return [$item['title'] => $item['mount']] ;
            })->toArray();
            
            $sumTotal = 0;
            foreach ($data as $key => $value) {
                $sumTotal += round(array_sum($value), 2);
                array_push($listItems, ['title' => $key , 'mount' => $sumTotal]);
            }
        }

        $datas = collect($listItems)->mapToGroups(function($item, $key){
            return [$item['title'] => $item['mount']];
        })->toArray();


        $series = array_keys($datas);
        $labels = array_map(fn($e) => array_sum($e) ,array_values($datas));

        return response()->json([
            'res' => true,
            'msg' => [$series, $labels, $totalAmount, $currentAmount]
        ]);
    }
}
