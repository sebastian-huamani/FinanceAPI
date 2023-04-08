<?php

namespace App\Http\Controllers;

use App\Models\sessionDivice;
use Illuminate\Http\Request;

class SessionDiviceController extends Controller
{

    public function showAll()
    {
        try {
            $divices = sessionDivice::where('user_id', auth()->user()->id)->get();

            return response()->json([
                'res' => true,
                'msg' => $divices
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'res' => true,
                'msg' => $e->getMessage()
            ]);
        }
    }

   
}
