<?php

namespace App\Http\Controllers;

use App\Http\Requests\TemplateRequest;
use App\Models\Template;
use Carbon\Carbon;
use DateTimeZone;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TemplateController extends Controller
{
    
    public function create(TemplateRequest $request)
    {
        $template = new Template();
        $template->title = $request->title;
        $template->body = $request->body;
        $template->state = $request->state;
        $template->user_id = auth()->user()->id;
        $template->created_at = Carbon::now(new DateTimeZone('America/Lima'));
        $template->save();

        return response()->json([
            'res' => true,
            'msg' => "Se Ha agregado una Nueva Plantilla",
        ], 200);
    }

    public function showAll()
    {
        $templates = Template::where('user_id', auth()->user()->id)->get();
        
        return response()->json([
            'res' => true,
            'msg' => $templates,
        ], 200);
    }

    public function showOne(Request $request)
    {
        try {
            $template = Template::where('id', $request->id)->where('user_id', auth()->user()->id)->first();

            if( !$template ){
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

    public function update(TemplateRequest $request, $id)
    {
        try {
            $template = Template::where('user_id',  auth()->user()->id)->where('id', $id)->first();
            
            
            if( !$template ){
                throw new Exception();
            }

            $template->title = $request->title;
            $template->body = $request->body;
            $template->state = $request->state;
            $template->updated_at = Carbon::now(new DateTimeZone('America/Lima'));
            $template->save();
    
            return response()->json([
                'res' => true,
                'msg' => "Se Ha Actualizado La Plantilla"
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'res' => false,
                'msg' => "Se Ha Producido Un Error, Informacion No Encontrada",
            ], 200);
        }
    }

    
    public function destroy(Request $request)
    {
        $deleted = DB::table('templates')->where('id','=',$request->id)->delete();
        
        $res = $deleted > 0 ? true : false;
        $msg = $deleted > 0 ? 'Se a eliminado la plantilla': 'No existe la plantilla a eliminar';

        return response()->json([
            'res' => $res,
            'msg' => $msg,
        ], 200);
    }
}
