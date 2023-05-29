<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\DataInfoUser;
use App\Models\Item;
use App\Models\sessionDivice;
use App\Models\Template;
use App\Models\User;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(AuthRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'lastname' => $request->lastname,
                'password' => Hash::make($request->password)
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'res' => true,
                'access_token' => $token,
                'token_type' => 'Bearer'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'e' => $e->getMessage()
            ], 200);
        }
    }

    public function login(Request $request)
    {

        $dateNow = Carbon::now(new DateTimeZone('America/Lima'));

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'res' => false,
                'msg' => 'Correo o Contraseña Invalidas',
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        if ($user->email_verified_at == null) {
            return response()->json([
                'res' => false,
                'msg' => 'Esta cuenta aun no esta verificada'
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'res' => true,
            'access_token' => $token,
            'token_type' => 'Bearer'
            ]);
        
    }

    public function infoUser(Request $request)
    {
        try {
            return response()->json([
                'res' => true,
                'msg' => $request->user()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'msg' => $e->getMessage()
            ]);
        }
    }

    public function updateInfoUser(Request $request)
    {
        try {
            $user = User::where('id', auth()->user()->id)->first();

            $user->update([
                'name' => $request->name,
                'lastname' => $request->lastname,
            ]);

            return response()->json([
                'res' => true,
                'msg' => 'Actualizado Tu Perfil'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'res' => true,
                'msg' => 'Perfil No Actualizado',
                'e' => $e->getMessage()
            ]);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'res' => true,
            'msg' => 'Te has Deslogeado'
        ]);
    }

    public function data_info_users()
    {
        $dateNow = Carbon::now(new DateTimeZone('America/Lima'));
        $user = User::find(auth()->user()->id);

        $dataLandings = $user->landings()
            ->join('states', 'landings.state_id', '=', 'states.id')
            ->where('landings.state_id', 1)
            ->get();
 

        $totalLendings = round($dataLandings->sum("amount"), 2);
        $creditLineTotal = round($user->cards()->where('cards.type_card_id', 2)->sum("bottom_line"), 2);
        $creditAmountTotal = round($user->cards()->where('cards.type_card_id', 2)->sum("amount"), 2);
        $debitTotal =  round($user->cards()->where('cards.type_card_id', 1)->sum("amount"), 2);
        $debitAmountTotal = round($debitTotal, 2) - round($totalLendings, 2);


        $data = array(
            'full_credit' => $creditLineTotal,
            'aviable_credit' => $creditAmountTotal,
            'full_debit' => $debitTotal,
            'aviable_debit' => $debitAmountTotal,
            'user' => $user->id
        );

        try {
            DataInfoUser::create([
                'full_credit' => $creditLineTotal,
                'aviable_credit' => $creditAmountTotal,
                'full_debit' => $debitTotal,
                'aviable_debit' => $debitAmountTotal,
                'user_id' => $user->id,
                'created_at' => $dateNow
            ]);
            return response()->json([
                'res' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'e' => $e->getMessage()
            ]);
        }
    }

    public function unauthorized(Request $request)
    {
        return response()->json([
            "Mensaje" => "No autorizado, No se proporcionó Token o es invalido"
        ], 401);
    }

    public function allTransaction()
    {
        try {
            $user = User::where('id', auth()->user()->id)->first();

            $data = $user->templates()->get()->map(function(Template $template){
                $amount = Item::where('template_id', $template->id)->sum('amount');
                return [ $template->title, $amount];
            });

            return response()->json([
                'res' => true,
                'msg' => $data,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'e' => $e->getMessage()
            ]);
        }
    }

    public function trasactionMothYear(Request $request)
    {

        try {
            $data = [];
            $user = User::where('id', auth()->user()->id)->first();
            $templates = $user->templates;

            foreach ($templates as $template) {
                $amount = Item::where('template_id', $template->id)
                ->whereMonth('created_at',$request->month)
                ->whereYear('created_at',$request->year)
                ->sum('amount');
                array_push($data, [ $template->title, $amount]);
            }

            return response()->json([
                'res' => true,
                'msg' => $data,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'e' => $e->getMessage()
            ]);
        }
    }

    public function transactionsFromTo(Request $request)
    {
        try {
            $data = [];
            $user = User::where('id', auth()->user()->id)->first();
            $templates = $user->templates;

            foreach ($templates as $template) {
                $amount = Item::where('template_id', $template->id)
                ->whereDate('created_at', '>=', $request->from)
                ->whereDate('created_at', '<=', $request->to)
                ->sum('amount');
                array_push($data, [ $template->title, $amount]);
            }

            return response()->json([
                'res' => true,
                'msg' => $data,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'e' => $e->getMessage()
            ]);
        }
    }


    // 
    public function registerBase(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['required', 'string'],
            'lastname' => ['required', 'string'],
            'email' => ['required' ,'email', 'string'],
            'password' =>  ['required', 'string'],
            'repeatpassword' =>  ['required', 'string']
        ]);
        
        return $credentials;

        $user = new User();
        $user->name = $credentials['name'];
        $user->lastname = $credentials['lastname'];
        $user->email = $credentials['email'];
        $user->password = Hash::make($credentials['password']);
        $user->save();
         
        Auth::login($user);
        
        return redirect(route('dashboard'));
    }

    public function loginBase(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required' ,'email', 'string'],
            'password' =>  ['required', 'string']
        ]);

        $remember = request()->filled('remember');

        if(Auth::attempt($credentials, $remember)){
            request()->session()->regenerate();
            return redirect('dashboard')->with('status', 'Your are logged in');
        }

        throw ValidationException::withMessages([
            'credencials' => __('auth.failed')
        ]);
        return redirect('login');
    }

    public function logoutBase()
    {
        Auth::logout();

        return redirect('login');
    }
}
