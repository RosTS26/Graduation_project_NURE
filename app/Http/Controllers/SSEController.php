<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SSEController extends Controller
{
    public function stream(Request $request)
    {
        $response = new StreamedResponse(function () {
            while (true) {
                // Отправка данных на клиент
                echo "data: " . json_encode(['message' => 'Hello, world!']) . "\n\n";
                ob_flush();
                flush();
                sleep(10); // Пауза перед следующим обновлением
            }
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');

        return $response;
    }
}