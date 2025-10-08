<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OllamaController;

Route::post('/ollama/generate', [OllamaController::class, 'generate']);
