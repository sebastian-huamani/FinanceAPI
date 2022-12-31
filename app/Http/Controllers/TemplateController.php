<?php

namespace App\Http\Controllers;

use App\Http\Requests\TemplateRequest;
use App\Models\Template;
use App\Models\User;
use Carbon\Carbon;
use DateTimeZone;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TemplateController extends Controller
{

    public function create(Request $request)
    {
        try {
            $body = [];
            for ($i=0; $i < sizeof($request->body) ; $i++) { 
                array_push($body ,[$request->body[$i], $request->type[$i]] );
            }

            Template::create([
                'title' => $request->title,
                'body' => $body,
                'states_id' => $request->states_id,
                'user_id' => auth()->user()->id,
                'created_at' => Carbon::now(new DateTimeZone('America/Lima'))
            ]);

            return response()->json([
                'res' => true,
                'msg' => "Se Ha agregado una Nueva Plantilla",
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'msg' => $e->getMessage(),
            ], 200);
        }
    }

    public function showAll()
    {
        try {
            $templates = DB::table('templates')
                ->join('states', 'templates.states_id', '=', 'states.id')
                ->select('templates.id', 'templates.title', 'templates.body', 'states.name as state', 'templates.created_at')
                ->where('user_id', auth()->user()->id)  
                ->orderBy('templates.id', 'desc')
                ->get();

            return response()->json([
                'res' => true,
                'msg' => $templates,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'msg' => $e->getMessage()
            ], 200);
        }
    }

    public function showOne(Request $request)
    {
        try {
            $template = Template::where('id', $request->id)->where('user_id', auth()->user()->id)->first();

            if (!$template) {
                throw new Exception();
            }

            return response()->json([
                'res' => true,
                'msg' => $template,
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
            
            $body = [];
            for ($i=0; $i < sizeof($request->body) ; $i++) { 
                array_push($body ,[$request->body[$i], $request->type[$i]] );
            }
            $template = Template::where('user_id',  auth()->user()->id)->where('id', $id)->first();
            
            if (!$template) {
                throw new Exception();
            }

            $template->update([
                'title' => $request->title,
                'body' => $body,
                'states_id' => $request->states_id,
                'user_id' => auth()->user()->id,
                'created_at' => Carbon::now(new DateTimeZone('America/Lima'))
            ]);

            return response()->json([
                'res' => true,
                'msg' => "Se Ha Actualizado La Plantilla",
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'msg' => "Se Ha Producido Un Error, Informacion No Encontrada",
            ], 200);
        }
    }


    public function destroy($id)
    {
        $deleted = Template::where('id', $id)->where('user_id', auth()->user()->id)->delete();

        $res = $deleted > 0 ? true : false;
        $msg = $deleted > 0 ? 'Se a eliminado la plantilla' : 'No existe la plantilla a eliminar';

        return response()->json([
            'res' => $res,
            'msg' => $msg,
        ], 200);
    }
}
