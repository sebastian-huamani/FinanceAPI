<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
use App\Http\Requests\TransactionCardsRequest;
use App\Http\Requests\TransactionRequest;
use App\Models\Card;
use App\Models\DataInfoUser;
use App\Models\Item;
use App\Models\Landing;
use App\Models\User;
use Carbon\Carbon;
use DateTimeZone;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function deleteItemsOfCard(Request $request)
    {
        try {
            if (auth()->user()->id != 2) {
                throw new Exception();
            }

            $card = Card::where('id', $request->id)->first();
            
            $card->items->each(function(Item $item){
                $item->delete();
            });

            $card->items()->detach();
    
            return response()->json([
                'card' => $card
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'res' => $e->getMessage(),
                'msg' => 'no tienes permisos para hacer esta accion'
            ], 404);
        }
    }

    public function createItemCount(TransactionRequest $request)
    {
        DB::beginTransaction();

        try {
            $history_quota = [];
            if( $request->has('lending') || $request->has('fee_amount') ){
                if( $request->has('fee_amount')){
                    $mount = $request->amount < 0 ? $request->amount * -1 :  $request->amount;
                    $avg = round($mount / $request->fee_amount, 2);
                    for ($i=0; $i < $request->fee_amount ; $i++) { 
                        $dateNow = Carbon::now(new DateTimeZone('America/Lima'));
                        if($request->type_date_payment == 1){
                            $dateNow->addMonth();
                            $lastday = date("Y-m-t", strtotime($dateNow->toDateString()));   
                            $date_f = Carbon::createFromFormat('Y-m-d', $lastday);
                        }
                        if($request->type_date_payment == 2){
                            $dateNow->addMonth();
                            $date_f = Carbon::createFromFormat('Y-m-d',  $dateNow->year . '-' . $dateNow->month .  '-01');
                        }
                        if($request->type_date_payment == 3){
                            $date_f = Carbon::createFromFormat('Y-m-d',  $request->date_payment_especial);
                        }
                        array_push($history_quota, [$i , $avg, $date_f, 2]);
                        $date_f->addMonths($i);
                    }
                } 
                
                $lending = Landing::create([
                    'state_id' => 1,
                    'amount' => $request->amount,
                    'history_quota' => $history_quota,
                    'is_lending' => $request->has('lending') ? 1 : 0,
                    'is_fee' => $request->has('fee_amount') ? 1 : 0,
                    'debtor' => $request->has('lending') ? $request->lending : null,
                    'card_id' => $request->cards_id,
                ]);
            }

            $body = [];
            for ($i=0; $i < sizeof($request->body) ; $i++) { 
                array_push($body ,[$request->template[$i], $request->body[$i], $request->type[$i]] );
            }

            $card = Card::where('id', $request->cards_id)->where('user_id', auth()->user()->id)->first();

            $card->items()->create([
                'title'=> $request->title, 
                'body' => $body,
                'amount'=> $request->amount,
                'landing_id' => isset($lending) ? $lending->id : null, 
                'template_id' => $request->template_id,
                'created_at' => $request->register_Item,
            ]);

            $card->update([
                'amount'=> $card->amount + $request->amount
            ]);

            DB::commit();

            return response()->json([
                'res' => true,
                'msg' => "Se Ha Agregado A La Lista De Movimientos",
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'res' => false,
                'msg' => "Se ha generado un Error",
                'e' => $e->getMessage()
            ], 200);
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

    public function promedio($currentMoth, $lastMonth){
        $value = ( $currentMoth - $lastMonth) / $lastMonth;
        return round($value * 100, 2);
    }
    
    public function ActualCurrentMoney(){
        try {
            $dateNow = Carbon::now(new DateTimeZone('America/Lima'));
            $user = User::find(auth()->user()->id);
            
            $dataLandings = Landing::getSumActives();

            $totalLendings = round($dataLandings, 2);
            $creditLineTotal = $user->cards()->where('cards.type_card_id', 2)->sum("bottom_line");
            $creditAmountTotal = $user->cards()->where('cards.type_card_id', 2)->sum("amount");
            $debitTotal = $user->cards()->where('cards.type_card_id', 1)->sum("amount");
            $debitAmountTotal = round($debitTotal, 2) - round($totalLendings, 2);
            
            $fullCreditPorcent = 0;
            $aviableCreditPorcent = 0;
            $fullDebitPorcent = 0;
            $aviableDebitPorcent = 0;
            
            $lastDataMonth = $user->data_info_user()->whereDate('data_info_users.created_at', '<', $dateNow)->first();

            if ($lastDataMonth != null) {
                $fullCreditPorcent =  $this->promedio($creditLineTotal, $lastDataMonth["full_credit"]); 
                $aviableCreditPorcent = $this->promedio($creditAmountTotal,$lastDataMonth["aviable_credit"]);
                $fullDebitPorcent = $this->promedio($debitTotal,$lastDataMonth["full_debit"]);
                $aviableDebitPorcent = $this->promedio($debitAmountTotal,$lastDataMonth["aviable_debit"]);
            }

            $data = array(
                'full_credit' => array($creditLineTotal, $fullCreditPorcent),
                'aviable_credit' => array($creditAmountTotal, $aviableCreditPorcent),
                'full_debit' => array($debitTotal, $fullDebitPorcent),
                'aviable_debit' => array( round($debitAmountTotal, 2), $aviableDebitPorcent),
                'full_lending' => array($totalLendings, 0),
            );

            return response()->json([
                'res' => true,
                'msg' => $data,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'msg' => $e->getMessage(),
            ], 200);
        }
    }

    public function dataxMonth()
    {
        try {
            $dateNow = Carbon::now(new DateTimeZone('America/Lima'));
            $user = User::find(auth()->user()->id);
            
            $dataxMonth = $user->data_info_user()
                ->whereDate('data_info_users.created_at', '<', $dateNow->addDays(1))
                ->limit(12)
                ->orderBy('data_info_users.created_at', 'desc')
                ->get();

            return response()->json([
                'res' => true,
                'msg' => $dataxMonth,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'msg' => $e->getMessage(),
            ], 200);
        }
    }

    public function Lendings()
    {
        try {
            $user = User::find(auth()->user()->id);
            
            $dataLandings = $user->landings()
            ->join('states', 'landings.state_id', '=', 'states.id')
            ->where('landings.state_id', 1)
            ->get();

            return response()->json([
                'res' => true,
                'msg' => $dataLandings,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'msg' => $e->getMessage(),
            ], 200);
        }
    }

    public function DataDashboard()
    {
        try {
            $dateNow = Carbon::now(new DateTimeZone('America/Lima'));
            $user = User::find(auth()->user()->id);
            
            $dataLandings = $user->landings()
            ->join('states', 'landings.state_id', '=', 'states.id')
            ->where('landings.state_id', 1)
            ->get();

            $totalLendings = round($dataLandings->sum("amount"), 2);
            $creditLineTotal = $user->cards()->where('cards.type_card_id', 2)->sum("bottom_line");
            $creditAmountTotal = $user->cards()->where('cards.type_card_id', 2)->sum("amount");
            $debitTotal = $user->cards()->where('cards.type_card_id', 1)->sum("amount");
            $debitAmountTotal = round($debitTotal, 2) - round($totalLendings, 2);
            
            $fullCreditPorcent = 0;
            $aviableCreditPorcent = 0;
            $fullDebitPorcent = 0;
            $aviableDebitPorcent = 0;
            
            $lastDataMonth = $user->data_info_user()->whereDate('data_info_users.created_at', '<', $dateNow)->first();

            if ($lastDataMonth != null) {
                $fullCreditPorcent =  $this->promedio($creditLineTotal, $lastDataMonth["full_credit"]); 
                $aviableCreditPorcent = $this->promedio($creditAmountTotal,$lastDataMonth["aviable_credit"]);
                $fullDebitPorcent = $this->promedio($debitTotal,$lastDataMonth["full_debit"]);
                $aviableDebitPorcent = $this->promedio($debitAmountTotal,$lastDataMonth["aviable_debit"]);
            }

            $dataxMonth = $user->data_info_user()
                ->whereDate('data_info_users.created_at', '<', $dateNow->addDays(1))
                ->limit(12)
                ->orderBy('data_info_users.created_at', 'desc')
                ->get();

            $data = array(
                'full_credit' => array($creditLineTotal, $fullCreditPorcent),
                'aviable_credit' => array($creditAmountTotal, $aviableCreditPorcent),
                'full_debit' => array($debitTotal, $fullDebitPorcent),
                'aviable_debit' => array( round($debitAmountTotal, 2), $aviableDebitPorcent),
                'full_lending' => array($totalLendings, 0),
                'dataLending' => $dataLandings,
                'dataxMonth' => $dataxMonth,
            );

            return response()->json([
                'res' => true,
                'msg' => $data,
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
            $newBottomLine = (floatval($card->amount) - floatval($lastAmount)) + floatval($request->amount);

            $item->update([
                'body' => $body,
                'updated_at' => $dateNow,
                'amount' => $request->amount,
            ]);

            $card->update([
                'amount' => $newBottomLine
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

    public function transactionBetweenCards(TransactionCardsRequest $request){

        DB::beginTransaction();

        try {
            $dateNow = Carbon::now(new DateTimeZone('America/Lima'));

            $fromCard = Card::where('id', $request->fromCard)->where('user_id', auth()->user()->id)->first();
            $toCard = Card::where('id', $request->toCard)->where('user_id', auth()->user()->id)->first();

            if($fromCard == null || $toCard == null){
                throw new Exception();
            }

            $fromCard->items()->create([
                'title'=> "Transaccion entre Cuentas",
                'body' => [["Nombre", "Transaccion entre Cuentas"],["Cuenta Origen", $fromCard->name . " - " . $request->fromCard ],["Cuenta de Destino", $toCard->name . " - " . $request->toCard]],
                'amount'=> $request->amount * -1,
                'landing_id' => null,
                'template_id' => 1,
                'created_at' => $dateNow,
            ]);

            $toCard->items()->create([
                'title'=> "Transaccion entre Cuentas",
                'body' => [["Nombre", "Transaccion entre Cuentas"],["Cuenta Origen", $fromCard->name . " - " . $request->fromCard ],["Cuenta de Destino", $toCard->name . " - " . $request->toCard]],
                'amount'=> $request->amount,
                'landing_id' => null,
                'template_id' => 1,
                'created_at' => $dateNow,
            ]);

            $fromCard->update([
                'amount'=> $fromCard->amount - $request->amount
            ]);

            $toCard->update([
                'amount'=> $toCard->amount + $request->amount
            ]);

            DB::commit();

            return response()->json([
                'res' => true,
                'msg' => "Transferencia Realizada",
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'res' => false,
                'msg' => "Transferencia no Realizada",
            ], 200);
        }
    }
}
