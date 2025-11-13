<?php

namespace App\Http\Controllers;

use App\Services\SmsEmpresarialService;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function index(){
        return view('admin.sms');
    }
    public function send(Request $request, SmsEmpresarialService $smsService)
    {
        $request->validate([
            'telefono' => 'required|string',
            'mensaje'  => 'required|string|max:160',
        ]);

        $result = $smsService->send($request->telefono, $request->mensaje);

        if ($result['ok']) {
            return response()->json([
                'message' => 'SMS enviado correctamente',
                'data'    => $result['data'],
            ]);
        }

        return response()->json([
            'message' => 'Error al enviar SMS',
            'error'   => $result['error'],
        ], 500);
    }
}
