<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsEmpresarialService
{
    public function send(string $telefono, string $mensaje): array
    {
        $url    = config('services.sms_empresarial.url');
        $apiKey = config('services.sms_empresarial.api_key');

        $payload = [
            'APIKEY'   => $apiKey,
            'TELEFONO' => $telefono,
            'MENSAJE'  => $mensaje,
        ];

        // Envía el POST como JSON
        $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])
            ->post($url, $payload);

        // Aquí puedes adaptar según qué devuelva el servicio
        if ($response->successful()) {
            return [
                'ok'       => true,
                'status'   => $response->status(),
                'data'     => $response->json(), // o ->body()
            ];
        }

        return [
            'ok'       => false,
            'status'   => $response->status(),
            'error'    => $response->body(),
        ];
    }
}
