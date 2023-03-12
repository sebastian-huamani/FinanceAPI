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
use App\Models\User;
use Exception;

class CardController extends Controller
{

    public function createDebitCard(DebitCardRequest $request)
    {
        try {
            $dateNow = Carbon::now(new DateTimeZone('America/Lima'));

            Card::create([
                'name' => $request->name,
                'bottom_line' => 0,
                'amount' => $request->bottom_line,
                'name_banck' => $request->name_banck,
                'card_expiration_date' => $request->card_expiration_date,
                'type_card_id' => 1,
                'date_cards_id' => null,
                'state_id' => 1,
                'user_id' => auth()->user()->id,
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ]);

            return response()->json([
                'res' => true,
                'msg' => "Has Creado Una Nueva Cuenta Debito",
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'msg' => "Se Ha Producido Un Error, Cuenta No Creada",
                'e' => $e->getMessage()
            ], 200);
        }
    }

    public function createCreditCard(CreditCardRequest $request)
    {
        DB::beginTransaction();
        try {
            $dateNow = Carbon::now(new DateTimeZone('America/Lima'));

            $user = User::where('id', auth()->user()->id)->first();
            $dateCard = $user->date_card()->create([
                "billing_cycle" => 12,
                "closing_date" => 7,
                "payment_due_date" => 12,
            ]);

            Card::create([
                'name' => $request->name,
                'bottom_line' => $request->bottom_line,
                'amount' => $request->bottom_line,
                'name_banck' => $request->name_banck,
                'card_expiration_date' => $request->card_expiration_date,
                'type_card_id' => 2,
                'date_card_id' => $dateCard->id,
                'state_id' => 1,
                'user_id' => auth()->user()->id,
                'created_at' => $dateNow,
                'updated_at' => $dateNow,
            ]);

            DB::commit();

            return response()->json([
                'res' => true,
                'msg' => "Has Creado Una Nueva Cuenta Credito",
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'res' => false,
                'msg' => "Se Ha Producido Un Error, Cuenta No Creada",
            ], 200);
        }
    }

    public function showAll()
    {
        try {
            $data = Card::where('user_id', auth()->user()->id)
                ->where('state_id', 1)
                ->join('type_cards', 'cards.type_card_id', '=', 'type_cards.id')
                ->select('cards.id', 'cards.name', 'cards.bottom_line', 'cards.amount', 'cards.name_banck', 'type_cards.name as type_card')
                ->get();

            return response()->json([
                'res' => true,
                'msg' => $data,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'msg' => "Se Ha Producido Un Error, Informacion No Encontrada",
                'e' => $e->getMessage()
            ], 200);
        }
    }

    public function dataCardDebit($id_card)
    {
        $debitCard = Card::where('cards.id',  $id_card)
            ->join('type_cards', 'cards.type_card_id', '=', 'type_cards.id')
            ->select('cards.id', 'cards.name', 'cards.amount', 'cards.name_banck', 'cards.card_expiration_date', 'type_cards.name as type_card', 'cards.created_at', 'cards.updated_at')
            ->where('cards.user_id', auth()->user()->id)
            ->first();

        return $debitCard;
    }

    public function dataCardCrebit($id_card)
    {
        $debitCard = Card::where('cards.id',  $id_card)
            ->leftJoin('date_cards', 'cards.date_card_id', '=', 'date_cards.id')
            ->join('type_cards', 'cards.type_card_id', '=', 'type_cards.id')
            ->select('cards.id', 'cards.name', 'cards.bottom_line', 'cards.amount', 'cards.name_banck', 'cards.card_expiration_date', 'type_cards.name as type_card', 'cards.created_at', 'cards.updated_at', 'date_cards.user_id', 'date_cards.billing_cycle', 'date_cards.closing_date', 'date_cards.payment_due_date')
            ->where('cards.user_id', auth()->user()->id)
            ->first();

        return $debitCard;
    }

    public function showOne(Request $request)
    {
        try {
            $infoCard = 0;
            $data = Card::select('type_card_id')
                ->where('id', $request->id)
                ->first();


            if ($data->type_card_id == 1) {
                $infoCard = $this->dataCardDebit($request->id);
            } else {
                $infoCard = $this->dataCardCrebit($request->id);
            }

            return response()->json([
                'res' => true,
                'msg' => $infoCard
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
            $dateCard = null;
            $card = Card::where('user_id', '=', auth()->user()->id)->where('id', '=', $id)->first();

            if (!$card) {
                throw new Exception();
            }

            $card->name = $request->name;
            $card->amount = $request->amount;
            $card->name_banck = $request->name_banck;
            $card->card_expiration_date = $request->card_expiration_date;
            $card->updated_at = Carbon::now(new DateTimeZone('America/Lima'));
            $card->save();
            
            if ($card->date_cards_id != null) {
                $card->bottom_line = $request->bottom_line;
                $dateCard = DateCard::where('id', $card->date_card_id)->first();
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
                'e' => $e->getMessage()
            ], 200);
        }
    }

    public function UpdateState($id)
    {
        try {

            $card = Card::where('id', $id)->where('user_id', auth()->user()->id)->first();
            $card->update([
                'state_id' => 2
            ]);

            return response()->json([
                'res' => "true",
                'msg' => 'Se a eliminado la Cuenta Con Exito',
                'e' => $card
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'res' => "true",
                'msg' => 'Se Producido Un Error, Intenta de nuevo en unos segundos',
                'e' => $e->getMessage()
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
