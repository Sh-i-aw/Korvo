<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OllamaController extends Controller
{
    public function generate(Request $request): JsonResponse
    {
        // Validate incoming payload as needed
        $payload = $request->validate([
            'model'  => 'required|string',
            'prompt' => 'required|string',
            "format" => 'required|array',
            'stream' => 'boolean',
        ]);

        $resp = Http::timeout(30)->post('http://127.0.0.1:11434/api/generate', $payload);

        if ($resp->successful()) {
            $body = $resp->body();
            Log::info('Ollama raw response body:', ['body' => $body]);

            // Parse the single JSON response
            $data = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Failed to parse Ollama response as JSON', [
                    'body' => $body,
                    'error' => json_last_error_msg()
                ]);
                return response()->json(['error' => 'Invalid response format from Ollama'], 500);
            }

            $responseText = $data['response'] ?? 'No response received';

            return response()->json($responseText);
        }

        Log::error('Ollama request failed', [
            'status' => $resp->status(),
            'body' => $resp->body()
        ]);

        return response()->json(['error' => 'Failed to get response from Ollama'], 500);

    }
}
