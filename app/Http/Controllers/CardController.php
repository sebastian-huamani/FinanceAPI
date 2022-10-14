<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreditCardRequest;
use App\Http\Requests\DebitCardRequest;
use Exception;

class CardController extends Controller
{

    public function createDebitCard(DebitCardRequest $request)
    {
        try {
            $dateNow = Carbon::now(new DateTimeZone('America/Lima'));
            DB::statement('call SP_Create_DebitCard(?,?,?,?,?,?,?)', [
                $request->name,
                $request->bottom_line,
                $request->name_banck,
                $request->card_expiration_date,
                $request->type_cards_id,
                auth()->user()->id,
                $dateNow,
            ]);
            return response()->json([
                'res' => true,
                'msg' => "Se Ha Agregado Una Nueva Cuenta Debito",
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'msg' => "Se Ha Producido Un Error, Cuenta No Creada",
            ], 200);
        }
    }

    public function createCreditCard(CreditCardRequest $request)
    {
        try {
            $dateNow = Carbon::now(new DateTimeZone('America/Lima'));
            DB::statement('call SP_Create_CreditCard(?,?,?,?,?,?,?,?,?,?)', [
                $request->name,
                $request->bottom_line,
                $request->name_banck,
                $request->card_expiration_date,
                $request->type_cards_id,
                auth()->user()->id,
                $dateNow,
                $request->billing_cycle,
                $request->closing_date,
                $request->payment_due_date,
            ]);
            return response()->json([
                'res' => true,
                'msg' => "Se Ha Agregado Una Nueva Cuenta Credito",
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'msg' => "Se Ha Producido Un Error, Cuenta No Creada",
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    public function showAll()
    {
        $cards = Card::where('user_id', auth()->user()->id)->get();

        return response()->json([
            'res' => true,
            'msg' => $cards,
        ], 200);
    }

    public function showOne(Request $request)
    {
        try {
            $card = Card::where('id', $request->id)->where('user_id', auth()->user()->id)->first();
    
            if( !$card ){
                throw new Exception();
            }
    
            return response()->json([
                'res' => true,
                'msg' => $card
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'msg' => "Se Ha Producido Un Error, Informacion No Encontrada",
            ], 200);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $card = Card::where('user_id', '=', auth()->user()->id)->where('id', '=', $id)->first();

            if( !$card ){
                throw new Exception();
            }
            
            $card->name = $request->name;
            $card->bottom_line = $request->bottom_line;
            $card->name_banck = $request->name_banck;
            $card->card_expiration_date = $request->card_expiration_date;
            $card->updated_at = Carbon::now(new DateTimeZone('America/Lima'));
            $card->save();
            
            return response()->json([
                'res' => true,
                'msg' => "Se Ha Actualizado La Cuenta"
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'msg' => "Se Ha Producido Un Error, Cuenta No Actualizada",
                'e' => $e->getMessage()
            ], 200);
        }
    }

    public function destroy($id)
    {
        $deleted = DB::statement('call SP_Delete_Card(?,?)', [$id, auth()->user()->id]);

        $res = $deleted > 0 ? true : false;
        $msg = $deleted > 0 ? 'Se a eliminado la Cuenta Con Exito' : 'No existe la plantilla a eliminar';

        return response()->json([
            'res' => $res,
            'msg' => $msg,
        ], 200);
    }
}
