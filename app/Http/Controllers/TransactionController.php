<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
use App\Models\Item;
use App\Models\Transaction;
use Carbon\Carbon;
use DateTimeZone;
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

    public function destory($id)
    {
        try {
            
            $item = Item::find($id);
            $card = Transaction::where('items_id', '=', $item->id)->first();
            $dateNow = Carbon::now(new DateTimeZone('America/Lima'));
            
            DB::statement('call SP_Delete_Item(?, ?, ?, ?)', [ 
                $item->id,
                $card->cards_id,
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
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function showAllItemsCount($id)
    {
        try {
            $data = Item::select('*')
                    ->join('transactions', 'transactions.items_id', '=', 'items.id')
                    ->join('cards', 'cards.id', '=', 'transactions.cards_id')
                    ->where('cards.id', '=', $id)->get();
            return response()->json([
                'res' => true,
                'msg' => $data,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'msg' => 'Hubo Un Error',
            ], 200);
        }
    }
    public function showOne($id)
    {
        try {
            $data = Item::find($id);

            return response()->json([
                'res' => true,
                'msg' => $data
            ]);

            
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'msg' => 'Parece que Hubo un Error',
                "e" => $e->getMessage()
            ]);
        }
    }

    public function editItemCount(ItemRequest $request)
    {
        try {
            $item = Item::find($request->id);
            $card = Transaction::where('items_id', '=', $item->id)->first();
            $dateNow = Carbon::now(new DateTimeZone('America/Lima'));
    
            DB::statement('call SP_Update_Item(?, ?, ?, ?, ?, ?, ?)', [
                $item->id,
                $card->cards_id,
                $request->title,
                json_encode($request->body),
                $item->amount,
                $request->amount,
                $dateNow
            ]);

            return response()->json([
                'res' => true,
                'msg' => $request->all()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'msg' => $e->getMessage()
            ]);
        }
    }


}
