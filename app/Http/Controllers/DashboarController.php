<?php

namespace App\Http\Controllers;

use App\Models\Card;
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

    public function dataxMonthTemplate($date){
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
                ->selectRaw("date_format(items.created_at, '%Y-%m-%d') as fecha, items.amount as mount, template_id as template")->get();

            $data = $items_temp->mapToGroups(function ($item, $key) {
                return [$item['template'] => $item['mount']];
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
}
