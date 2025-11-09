<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappController extends Controller
{
    public function sendMessage($nomor, $message)
    {
        if (substr($nomor, 0, 1) === "0") {
            $nomor = "62" . substr($nomor, 1);
        }
        try {

            if(env('APP_ENV') == 'local') {
                $nomor = '082240083432';
            }
            $response = Http::withHeaders([
                'apikey' => '391c116ccb676f98de04e39c20f9bee736656635'
            ])->post('https://starsender.online/api/sendText', [
                'tujuan' => $nomor,
                'message' => $message
            ]);

            return $response->json();

        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);
            return false;
        }
    }
}
