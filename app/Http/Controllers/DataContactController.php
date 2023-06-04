<?php

namespace App\Http\Controllers;

use App\Models\DataContact;
use Illuminate\Http\Request;

class DataContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function create(Request $request)
    {
        // return $request->all();
        try {
            $data = new DataContact();
            $data->name  = $request->name;
            $data->email  = $request->email;
            $data->description  = $request->description;
            $data->save();
    
            return view('/contact', [
                'message' => 'Correo Enviado',
                'status' => 'ok'
            ]);
        } catch (\Exception $th) {
            return view('/contact', [
                'message' => $th->getMessage(),
                'status' => 'error'
            ]);
        }
    }
}
