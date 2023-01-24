<?php

namespace App\Http\Controllers;

use App\Models\Landing;
use App\Models\User;
use Carbon\Carbon;
use DateTimeZone;
use Exception;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function create(Request $request){
        try {
            
            $user = User::where('id', auth()->user()->id)->first();

            $user->landings()->create([
                'debtor' => $request->debtor,
                'amount' => $request->amount,
                'payment_date' => $request->payment_date,
                'state_id' => 1
            ]);

            return response()->json([
                'res' => false,
                'msg' => 'Agregando Prestamo a tu lista'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'e' => $e->getMessage()
            ], 200);
        }
    }

    public function showAllActives()
    {
        try {
            $lendings = Landing::where('user_id', auth()->user()->id)
            ->join('states', 'landings.state_id', '=', 'states.id')
            ->where('state_id', 1)
            ->select('landings.id', 'landings.amount', 'landings.payment_date', 'landings.debtor', 'states.name as state', 'landings.created_at', 'landings.updated_at', 'landings.postpone')
            ->get();

            if (sizeof($lendings) == 0) {
                throw new Exception();
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

    public function showAllDesactives(Request $request)
    {
        try {
            $lendings = Landing::where('user_id', auth()->user()->id)
            ->join('states', 'landings.state_id', '=', 'states.id')
            ->where('state_id', 2)
            ->whereYear('landings.created_at', $request->year)
            ->whereMonth('landings.created_at', $request->month)
            ->select('landings.id', 'landings.amount', 'landings.payment_date', 'landings.debtor', 'states.name as state', 'landings.created_at', 'landings.updated_at', 'landings.postpone')
            ->get();

            if (sizeof($lendings) == 0) {
                throw new Exception();
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

    public function showOne(int $id){
        try {

            $lending = Landing::where('user_id', auth()->user()->id)->where('id', $id)->first();

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
        try {
            // $dateNow = Carbon::now(new DateTimeZone('America/Lima'));
            $lending = Landing::where('user_id', auth()->user()->id)->where('id', $request->id)->first();

            // $postpone = $lending->postpone;
            // array_push($postpone, [$dateNow, $request->amount, $request->payment_date]);

            $lending->update([
                'debtor' => $request->debtor,
                'amount' => $request->amount,
                'payment_date' => $request->payment_date,
                // 'postpone' => $postpone,
            ]);
            return response()->json([
                'res' => true,
                'msg' => 'Cuenta Actualizada',
                'lending' => $lending
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'e' => $e->getMessage()
            ]);
        }
    }
   
    public function updateState(int $id)
    {
        try {
            $lending = Landing::where('user_id', auth()->user()->id)->where('id', $id)->first();
            $lending->update(['state_id'=> 2 ]);

            return response()->json([
                'res' => true,
                'msg' => 'Prestamo Finalizado',
                'lending' => $lending
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'e' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            Landing::where('user_id', auth()->user()->id)->where('id', $id)->delete();

            return response()->json([
                'res' => true,
                'msg' => 'Se ha eliminado El prestamo',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'res' => true,
                'msg' => 'Se ha generado un error',
                'e' => $e->getMessage(),
            ], 200);
        }
    }
}
