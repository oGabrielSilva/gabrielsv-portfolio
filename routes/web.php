<?php

use App\Http\Controllers\CardGeneratorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ToolsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/card-generator', [CardGeneratorController::class, 'index'])->name('card-generator');

// Tools routes
Route::prefix('tools')->name('tools.')->group(function () {
    Route::get('/', [ToolsController::class, 'index'])->name('index');

    // UUID Generator - URLs amigáveis para SEO
    Route::get('/uuid', [ToolsController::class, 'uuid'])->name('uuid');
    Route::get('/uuid/{type}', [ToolsController::class, 'uuidByType'])->name('uuid.type');
    Route::post('/uuid/generate', [ToolsController::class, 'generateUuid'])->name('uuid.generate');

    // Lorem Ipsum Generator
    Route::get('/lorem', [ToolsController::class, 'lorem'])->name('lorem');
    Route::post('/lorem/generate', [ToolsController::class, 'generateLorem'])->name('lorem.generate');

    Route::get('/percentage', [ToolsController::class, 'percentage'])->name('percentage');
    Route::get('/image-compressor', [ToolsController::class, 'imageCompressor'])->name('image-compressor');

    // CPF/CNPJ Generator - URLs amigáveis para SEO
    Route::get('/cpf-cnpj', fn() => redirect()->route('tools.cpf'))->name('cpf-cnpj');
    Route::get('/cpf', [ToolsController::class, 'cpfCnpj'])->name('cpf')->defaults('type', 'cpf');
    Route::get('/cnpj', [ToolsController::class, 'cpfCnpj'])->name('cnpj')->defaults('type', 'cnpj');

    // Base64 Encoder/Decoder
    Route::get('/base64', [ToolsController::class, 'base64'])->name('base64');

    // Slugify
    Route::get('/slugify', [ToolsController::class, 'slugify'])->name('slugify');
});
