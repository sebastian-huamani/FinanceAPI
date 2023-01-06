<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
use App\Models\Card;
use App\Models\Item;
use App\Models\User;
use Carbon\Carbon;
use DateTimeZone;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{


    public function createItemCount(ItemRequest $request)
    {
        DB::beginTransaction();

        try {

            $dateNow = Carbon::now(new DateTimeZone('America/Lima'));

            $body = [];
            for ($i=0; $i < sizeof($request->body) ; $i++) { 
                array_push($body ,[$request->template[$i], $request->body[$i]] );
            }

            $card = Card::where('id', $request->cards_id)->where('user_id', auth()->user()->id)->first();

            $card->items()->create([
                'title'=> $request->title, 
                'body' => $body,
                'amount'=> $request->amount,
                'template_id' => $request->template_id,
                'created_at' => $dateNow
            ]);
                
            $card->update([
                'bottom_line'=> $card->bottom_line + $request->amount
            ]);

            DB::commit();

            return response()->json([
                'res' => true,
                'msg' => "Se Ha Agregado A La Lista De Movimientos",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'res' => false,
                'msg' => "Se ha generado un Error",
                'e' => $e->getMessage()
            ]);
        }
    }

    public function showAllItemsCount(Request $request)
    {
        try {
            $card = Card::where('cards.id', '=', $request->id_card)->first();
            
            $items = $card->items()
                ->whereYear('items.created_at', $request->year)
                ->whereMonth('items.created_at', $request->month)
                ->orderby('items.created_at', 'desc')
                ->get();

            return response()->json([
                'res' => true,
                'msg' => $items,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'msg' => "Se Ha Producido Un Error, Informacion No Encontrada",
            ], 200);
        }
    }

    public function DataDashboard()
    {
        try {
            $user = User::find(auth()->user()->id);
            
            $creditLineTotal = $user->cards()->where('cards.type_card_id', 2)->sum("bottom_line");
            $creditAmountTotal = $user->cards()->where('cards.type_card_id', 2)->sum("amount");
            $debitTotal = $user->cards()->where('cards.type_card_id', 1)->sum("amount");

            // $creditDebtxMonth = $user->cards()->where('cards.type_card_id', 2)->cursor()->filter(function ($card) {
            //     // $billing_cicle = $card->date_card->billing_cycle;
            //     // $closing_data = $card->date_card->closing_data;
            //     return $card->items;
            // });

            $data = array(
                'creditLineTotal' => array('Full Credit', $creditLineTotal, 132),
                'creditAmountTotal' => array('Available Credit', $creditAmountTotal, 132),
                'debitTotal' => array('Full Debit', $debitTotal, 132),
            );

            return response()->json([
                'res' => true,
                'msg' => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'msg' => $e->getMessage(),
            ], 200);
        }
    }

    public function showOne($id)
    {
        try {
            $item = Item::select('items.id', 'items.title', 'items.body', 'items.amount', 'items.created_at', 'items.updated_at')
            ->join('transactions', 'transactions.items_id', 'items.id')
            ->join('cards', 'cards.id', 'transactions.cards_id')
            ->where('cards.user_id', auth()->user()->id)
            ->where('items.id', $id)
            ->first();


            if (!$item) {
                throw new Exception();
            }

            return response()->json([
                'res' => true,
                'msg' => $item
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'res' => false,
                'msg' => "Se Ha Producido Un Error, Informacion No Encontrada",
            ]);
        }
    }

    public function editItemCount(Request $request)
    {
        DB::beginTransaction();
        try {
            $dateNow = Carbon::now(new DateTimeZone('America/Lima'));

            $body = [];
            foreach ($request->body as $key => $value) {
                array_push($body, [$key, $value]);
            }

            $card = Card::where('cards.user_id', auth()->user()->id)
                ->where('cards.id', $request->idCard)
                ->first();

            $item = $card->items()
                ->where('items.id', $request->idItem)
                ->first();

            $lastAmount = $item->amount;
            $newBottomLine = (floatval($card->bottom_line) - floatval($lastAmount)) + floatval($request->amount);

            $item->update([
                'body' => $body,
                'updated_at' => $dateNow,
                'amount' => $request->amount
            ]);

            $card->update([
                'bottom_line' => $newBottomLine
            ]);

            DB::commit();
            return response()->json([
                'res' => true,
                'msg' => "Cuenta Actualizada Con Exito",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'res' => false,
                'msg' => "Se Ha Producido Un Error, Informacion No Encontrada",
                'e' => $e->getMessage()
            ]);
        }
    }

    public function destory($id)
    {
        try {
            $item = Item::select('items.id as id_item', 'cards.id as id_card' , 'items.amount' )
            ->join('transactions', 'transactions.items_id', 'items.id')
            ->join('cards', 'cards.id', 'transactions.cards_id')
            ->where('cards.user_id', auth()->user()->id)
            ->where('items.id', $id)
            ->first();

            if ( !$item ) {
                throw new Exception();
            }

            $dateNow = Carbon::now(new DateTimeZone('America/Lima'));

            DB::statement('call SP_Delete_Item(?, ?, ?, ?)', [
                $item->id_item,
                $item->id_card,
                $item->amount,
                $dateNow
            ]);

            return response()->json([
                'res' => true,
                'msg' => 'Se ha eliminado con exito'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'res' => false,
                'msg' => "Se Ha Producido Un Error, Informacion No Encontrada",
            ]);
        }
    }

    public function showAllItemsHistory(){
        
    }
}
