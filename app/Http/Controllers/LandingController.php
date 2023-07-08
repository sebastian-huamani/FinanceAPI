<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Item;
use App\Models\Landing;
use App\Models\User;
use Carbon\Carbon;
use DateTimeZone;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use function GuzzleHttp\Promise\each;

class LandingController extends Controller
{
    public function create(Request $request)
    {
        DB::beginTransaction();
        try {
            $dateNow = Carbon::now(new DateTimeZone('America/Lima'));
            $user = User::where('id', auth()->user()->id)->first();

            //  tarjeta seleccionada
            $card = Card::where('id', $request->cards_id)->where('user_id', auth()->user()->id)->first();

            if($request->amount > $card->amount){
                DB::commit();
                return response()->json([
                    'res' => false,
                    'msg' => 'Cuenta con fondos insuficientes'
                ], 200);
            }

            $user->landings()->create([
                'debtor' => $request->debtor,
                'amount' => $request->amount,
                'created_date_lending' => $request->created_date_lending,
                'payment_date_lending' => $request->payment_date_lending,
                'state_id' => 1,
                'card_id' => $request->cards_id,
                'created_at' => $dateNow,
                'updated_at' => $dateNow
            ]);

            $card->update([
                'amount'=> $card->amount - $request->amount
            ]);

            DB::commit();

            return response()->json([
                'res' => true,
                'msg' => 'Agregando Prestamo a tu lista'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'res' => false,
                'msg' => 'Prestamo No Creado'
            ], 200);
        }
    }

    public function showAllActives()
    {
        try {
            $cardsByUser = Card::where('user_id', Auth::user()->id)->pluck("id");
            $lending = [];

            foreach ($cardsByUser as $card_id) {
                $card_ins = Card::find($card_id);
                $items = $card_ins->items()->whereNot('landing_id', null )
                ->leftjoin('landings', 'items.landing_id', 'landings.id')
                ->select('landings.*', 'items.*')
                ->get();
                foreach ($items as $item) {

                    $type_lending = [];
                    if($item['is_lending'] != 0){
                        array_push($type_lending, ['title' => 'Prestamo', 'colorSelected' => "bg-green-200", 'colorSelectedText' => "text-green-900"]);
                    }
                    if($item['is_fee'] != 0){
                        array_push($type_lending, ['title' => 'Cuotas', 'colorSelected' => "bg-blue-300", 'colorSelectedText' => "text-blue-900"]);
                    }

                    $itemOrder = [
                        'id' => $item['id'],
                        'title' => $item['title'],
                        'created_at' => $item['created_at'],
                        'type_lending' => $type_lending,
                        'state' => $item['state_id'],
                        'bank' => $card_ins['name'],
                        'lending_id' => $item['landing_id']
                    ];

                    array_push($lending, $itemOrder);
                }
            }

            return response()->json([
                'res' => true,
                'msg' => $lending, 
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'msg' => "Se Ha Producido Un Error",
                'e' => $e->getMessage()
            ], 200);
        }
    }

    public function showAllDesactives(Request $request)
    {
        try {
            $lendings = Landing::where('user_id', auth()->user()->id)
                ->join('states', 'landings.state_id', '=', 'states.id')
                ->where('state_id', 2)
                ->whereYear('landings.created_at', $request->year)
                ->whereMonth('landings.created_at', $request->month)
                ->select('landings.id', 'landings.amount', 'landings.created_date_lending', 'landings.payment_date_lending', 'landings.debtor', 'states.name as state', 'landings.created_at', 'landings.updated_at', 'landings.card_id', 'landings.type_lending')
                ->get();

            if (sizeof($lendings) == 0) {
                return response()->json([
                    'res' => true,
                    'data' => null,
                    'msg' => "No se encontraron datos en este mes",
                ], 200);
            }

            return response()->json([
                'res' => true,
                'msg' => $lendings,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'msg' => "Se Ha Producido Un Error",
                'e' => $e->getMessage()
            ], 200);
        }
    }

    public function filter_lending($state, $card , $month, $year) {

        try {
            $data = [];

            if($card == 0){
                $cardsByUser = Card::where('user_id', Auth::user()->id)->pluck("id");
            }else {
                $cardsByUser = Card::where('id', $card)->pluck("id");
            }

            foreach ($cardsByUser as $card_id) {
                $card_ins = Card::find($card_id);
                $items = $card_ins->items()->FilterLending($state , $month, $year)
                    ->select('landings.*', 'items.*')
                    ->get();

                foreach ($items as $item) {
                    array_push($data, $item);
                }

            }
            return response()->json([
                'res' => true,
                'msg' => $data, 
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'msg' => "Se Ha Producido Un Error",
                'e' => $e->getMessage()
            ], 200);
        }
        

    }

    public function showOne(int $id)
    {
        try {
            $item = Item::where('landing_id', $id)->first();
            $lending = Landing::find($id);

            $data = $this->orderItemLending($item, $lending);
            $lending->history_quota;
            // $this->updatingState($lending->history_quota);
            return response()->json([
                'res' => true,
                'msg' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function orderItemLending(Item $item, Landing $landing) {

            $type_lending = [];
            if($landing['is_lending'] != 0){
                array_push($type_lending, ['title' => 'Prestamo', 'colorSelected' => "bg-green-200", 'colorSelectedText' => "text-green-900"]);
            }
            if($landing['is_fee'] != 0){
                array_push($type_lending, ['title' => 'Cuotas', 'colorSelected' => "bg-blue-300", 'colorSelectedText' => "text-blue-900"]);
            }

        $order_list = [
            'id_item' => $item->id,
            'id_landing' => $landing->id,
            'body' => $item->body,
            'amount_item' => $item->amount,
            'amount_landing' => $landing->amount,
            'payment_date_lending' => $landing->payment_date_lending,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at,
            'state_id' => $landing->state_id ,
            'debtor' => $landing->debtor ,
            'history_quota' => $landing->history_quota,
            'type_landing' => $type_lending,
        ];

        return $order_list;
    }

    public function edit(Request $request)
    {
        DB::beginTransaction();
        try {
            $value = 0;
            $history_quota = [];
            if($request->has('type_state_payment')){
                for ($i=0; $i < sizeof($request->type_state_payment) ; $i++) { 
                    $value += $request->amountxMonth[$i];
                    if ($request->amountxMonth[$i] == '' || $request->amountxMonth[$i] <= 0 ){
                        return response()->json(['res' => false, 'msg' => 'La cuota ' . $i + 1 . ' esta vacia']);
                    }
                    if($request->date_pay[$i] == '' || $request->date_pay[$i] == null){
                        return response()->json(['res' => false, 'msg' => 'La Fecha de la columna ' . $i + 1 . ' esta vacia']);
                    }
                    array_push($history_quota, [$i, $request->amountxMonth[$i], $request->date_pay[$i], $request->type_state_payment[$i]]);
                }
            }

            $newAmount = $request->amount > 0 ? $request->amount : $request->amount * -1;

            if($value != $newAmount && $value != 0){
                return response()->json(['res' => false, 'msg' => 'las suma de las cuotas no coinciden con el monto'], 200);
            }

            $item = Item::where('id', $request->id)->first();
            $lending = Landing::where('id', $item->landing_id)->first();
            $card = Card::where('id', $item->cards->first()->id)->first();

            $lendingLastAmount = $item->amount;

            if($newAmount > $card->amount) {
                DB::commit();
                return response()->json([
                    'res' => false,
                    'msg' => 'Cuenta con fondos insuficientes',
                    'lending' => $lending
                ], 200);
            }

            $item->update([
                'amount' => $request->amount
            ]);

            $lending->update([
                'debtor' => $request->debtor,
                'payment_date_lending' => $request->payment_date_lending,
                'history_quota' => $history_quota,
                'updated_at' => Carbon::now(new DateTimeZone('America/Lima')),
            ]);
            
            $card->update([
                'amount' => ($card->amount + $request->amount) - $lendingLastAmount
            ]);
            
            DB::commit();
            return response()->json([
                'res' => true,
                'msg' => 'Prestamo actualizado',
                'lending' => $lending
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'res' => false,
                'msg' => 'No se pudieron editar los datos',
                'e' => $e->getMessage()
            ]);
        }
    }

    public function updateState(int $id)
    {   
        DB::beginTransaction();
        try {
            $lending = Landing::where('user_id', auth()->user()->id)->where('id', $id)->first();
            $lending->update(['state_id' => 2]);

            $card = Card::where('id', $lending->card_id)->where('user_id', auth()->user()->id)->first();
            $card->update([
                'amount'=> $card->amount + $lending->amount
            ]);

            DB::commit();

            return response()->json([
                'res' => true,
                'msg' => 'Prestamo Finalizado',
                'lending' => $lending
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'res' => false,
                'e' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $lending = Landing::where('user_id', auth()->user()->id)
                ->where('id', $id)
                ->where('state_id', 2)
                ->delete();

            if($lending == 0){
                throw new Exception();
            }

            DB::commit();
            return response()->json([
                'res' => true,
                'msg' => 'Se ha eliminado El prestamo',
                'e' => $lending
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'res' => false,
                'msg' => 'No se puede eliminar prestamos activos',
                'e' => $e->getMessage(),
            ], 200);
        }
    }
}
