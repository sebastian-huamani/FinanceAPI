<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
use App\Models\Item;
use App\Models\Transaction;
use Carbon\Carbon;
use DateTimeZone;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{


    public function createItemCount(ItemRequest $request)
    {
        try {
            $dateNow = Carbon::now(new DateTimeZone('America/Lima'));

            $item = new Item();
            $item->title = $request->title;
            $item->body = $request->body;
            $item->amount = $request->amount;
            $item->created_at = $dateNow;
            $item->save();


            DB::statement('call SP_Create_Item(?, ?, ?, ?)', [
                $request->cards_id,
                $item->id,
                $request->amount,
                auth()->user()->id
            ]);

            return response()->json([
                'res' => true,
                'msg' => "Se Ha Agregado A La Lista De Movimientos",
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'msg' => "Se ha generado un Error",
            ]);
        }
    }

    public function showAllItemsCount(Request $request)
    {
        try {
            $items = Item::select('items.id', 'items.title', 'items.body', 'items.amount', 'items.created_at', 'Items.updated_at')
                ->join('transactions', 'transactions.items_id', '=', 'items.id')
                ->join('cards', 'cards.id', '=', 'transactions.cards_id')
                ->where('cards.id', '=', $request->id_card)
                ->where('cards.user_id', auth()->user()->id)
                ->whereYear('items.created_at', $request->year)
                ->whereMonth ('items.created_at', $request->month)
                ->orderBy('items.created_at', 'desc')
                ->get();

            // $items = DB::select('CALL SP_Show_All_Items(?, ?, ?, ?)', [
            //     $request->id_card, 
            //     auth()->user()->id,
            //     $request->year,
            //     $request->month
            // ]);

            // if (!$items->first()) {
            //     throw new Exception();
            // }

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

    public function showAllItemsUser()
    {
        try {
            $items = Item::select('*')
                ->join('transactions', 'transactions.items_id', '=', 'items.id')
                ->join('cards', 'cards.id', '=', 'transactions.cards_id')
                ->where('cards.user_id', auth()->user()->id)
                ->get();

            if (!$items->first()) {
                throw new Exception();
            }

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

    public function editItemCount(ItemRequest $request)
    {
        try {

            $item = Item::select('items.id', 'items.title', 'items.body', 'items.amount', 'items.created_at', 'items.updated_at', 'transactions.cards_id', )
            ->join('transactions', 'transactions.items_id', 'items.id')
            ->join('cards', 'cards.id', 'transactions.cards_id')
            ->where('cards.user_id', auth()->user()->id)
            ->where('items.id', $request->id)
            ->first();

            $dateNow = Carbon::now(new DateTimeZone('America/Lima'));


            $res = DB::statement('call SP_Update_Item(?, ?, ?, ?, ?, ?, ?)', [
                $item->id,
                $item->cards_id,
                $request->title,
                json_encode($request->body),
                $item->amount,
                $request->amount,
                $dateNow
            ]);

            return response()->json([
                'res' => true,
                'msg' => "Cuenta Actualizada Con Exito",
                'item' => $item,
                'ress' => $res,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'msg' => "Se Ha Producido Un Error, Informacion No Encontrada",
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
}
