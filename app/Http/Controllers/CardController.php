<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CreditCardRequest;
use App\Http\Requests\DebitCardRequest;
use App\Models\DateCard;
use Exception;

class CardController extends Controller
{

    public function createDebitCard(DebitCardRequest $request)
    {
        try {
            $dateNow = Carbon::now(new DateTimeZone('America/Lima'));
            DB::statement('call SP_Create_DebitCard(?,?,?,?,?,?,?,?)', [
                $request->name,
                $request->bottom_line,
                $request->name_banck,
                $request->card_expiration_date,
                1,
                1,
                auth()->user()->id,
                $dateNow,
            ]);
            return response()->json([
                'res' => true,
                'msg' => "Has Creado Una Nueva Cuenta Debito",
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'msg' => "Se Ha Producido Un Error, Cuenta No Creada"
            ], 200);
        }
    }

    public function createCreditCard(CreditCardRequest $request)
    {
        try {
            $dateNow = Carbon::now(new DateTimeZone('America/Lima'));
            DB::statement('call SP_Create_CreditCard(?,?,?,?,?,?,?,?,?,?,?)', [
                $request->name,
                $request->bottom_line,
                $request->name_banck,
                $request->card_expiration_date,
                2,
                1,
                auth()->user()->id,
                $dateNow,
                $request->billing_cycle,
                $request->closing_date,
                $request->payment_due_date,
            ]);
            return response()->json([
                'res' => true,
                'msg' => "Has Creado Una Nueva Cuenta Credito",
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'msg' => "Se Ha Producido Un Error, Cuenta No Creada"
            ], 200);
        }
    }

    public function showAll()
    {
        try {
            $data = DB::select('CALL SP_Show_Cards(?)', [auth()->user()->id]);
            
            if ( !$data ) {
                throw new Exception();
            }
            
            return response()->json([
                'res' => true,
                'msg' => $data,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'res' => false,
                'msg' => "Se Ha Producido Un Error, Informacion No Encontrada",
            ], 200);
        }
    }

    public function showOne(Request $request)
    {
        try {
            $data = DB::select('CALL SP_ShowData_Cards(?, ?)', [$request->id, auth()->user()->id]);
            
            if ( !$data ) {
                throw new Exception();
            }

            return response()->json([
                'res' => true,
                'msg' => $data[0]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'msg' => "Se Ha Producido Un Error, Informacion No Encontrada",
                'e' => $e->getMessage()
            ], 200);
        }

    }

    public function update(Request $request, $id)
    {
        try {
            $dateCard = null;
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

            if ($card->date_cards_id != null) {
                $dateCard = DateCard::where('id', $card->date_cards_id)->first();
                $dateCard->billing_cycle = $request->billing_cycle;
                $dateCard->closing_date = $request->closing_date;
                $dateCard->payment_due_date = $request->payment_due_date;
                $dateCard->save();
            }
            
            return response()->json([
                'res' => true,
                'msg' => "Has Actualizado La Cuenta",
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'msg' => "Se Ha Producido Un Error, Cuenta No Actualizada",
            ], 200);
        }
    }

    public function UpdateState($id)
    {
        try {
            DB::statement('call SP_Update_State(?,?,?)', [
                $id,
                auth()->user()->id,
                2
            ]);
    
            return response()->json([
                'res' => "true",
                'msg' => 'Se a eliminado la Cuenta Con Exito',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'res' => "true",
                'msg' => 'Se Producido Un Error, Intenta de nuevo en unos segundos',
            ], 200);
        }
    }

    public function destroy($id)
    {
        $deleted = DB::statement('call SP_Delete_Card(?,?)', [$id, auth()->user()->id]);

        $res = $deleted > 0 ? true : false;
        $msg = $deleted > 0 ? 'Se a eliminado la Cuenta Con Exito' : 'No existe la Cuenta a eliminar';

        return response()->json([
            'res' => $res,
            'msg' => $msg,
        ], 200);
    }

    
}
