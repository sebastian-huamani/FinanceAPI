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
                $item = $card_ins->items()->whereNot('landing_id', null )->get();
                foreach ($item as $it) {
                    array_push($lending, $it);
                }
            }

            if(sizeof($lending) == 0 ){
                return response()->json([
                    'res' => true,
                    'msg' => 'No se encontaron tarjetas', 
                ], 200);
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
                $items = $card_ins->items()->whereNot('landing_id', null)
                    ->whereYear('items.created_at', $year)
                    ->whereMonth('items.created_at', $month)
                    ->orderby('items.created_at', 'desc')
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

            // $lending = Landing::where('user_id', auth()->user()->id)->where('id', $id)->first();
            $lending = Item::where('items.id', $id)->join('landings', 'items.landing_id', 'landings.id')->get();

            return response()->json([
                'res' => true,
                'msg' => $lending
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function edit(Request $request)
    {
        DB::beginTransaction();
        try {
            $lending = Landing::where('user_id', auth()->user()->id)->where('id', $request->id)->first();
            $card = Card::where('id', $lending->card_id)->first();
            $lendingLastAmount = $lending->amount;
            
            if($request->amount > $card->amount) {
                DB::commit();
                return response()->json([
                    'res' => false,
                    'msg' => 'Cuenta con fondos insuficientes',
                    'lending' => $lending
                ], 200);
            }
            
            $lending->update([
                'debtor' => $request->debtor,
                'amount' => $request->amount,
                'created_date_lending' => $request->created_date_lending,
                'payment_date_lending' => $request->payment_date_lending,
            ]);
            
            $card->update([
                'amount' => ($card->amount + $lendingLastAmount) - $request->amount
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
