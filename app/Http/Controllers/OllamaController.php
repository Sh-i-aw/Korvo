<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OllamaController extends Controller
{
    public function generate(Request $request)
    {
        // Validate incoming payload as needed
        $payload = $request->validate([
            'model'  => 'required|string',
            'prompt' => 'required|string',
            'stream' => 'boolean',
        ]);

        // If NOT streaming, parse the response and return just the final text
        $resp = Http::post('http://127.0.0.1:11434/api/generate', $payload);

        if ($resp->successful()) {
            $body = $resp->body();
//            Log::info('Ollama raw response body:', ['body' => $body]);

            // Parse the single JSON response
//            TODO: look up why associative has to be true here
            $data = json_decode($body, true);
            $responseText = $data['response'] ?? 'No response received';

            return response($responseText, 200)->withHeaders([
                'Content-Type' => 'text/plain',
            ]);
        }

        return response('Error: Failed to get response from Ollama', 500);

    }
}
