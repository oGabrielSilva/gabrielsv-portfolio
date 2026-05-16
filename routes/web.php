<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\ToolsController;
use App\Http\Controllers\WorldClockController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('index');
Route::view('/card-generator', 'card-generator')->name('card-generator');
Route::view('/brand-guide', 'brand-guide')->name('brand-guide');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Tools routes
Route::prefix('tools')->name('tools.')->group(function () {
    Route::get('/', [ToolsController::class, 'index'])->name('index');

    // UUID Generator - URLs amigáveis para SEO
    Route::get('/uuid', [ToolsController::class, 'uuid'])->name('uuid');
    Route::get('/uuid/{type}', [ToolsController::class, 'uuidByType'])->name('uuid.type');
    Route::post('/uuid/generate', [ToolsController::class, 'generateUuid'])
        ->name('uuid.generate')
        ->middleware('throttle:60,1');

    // Lorem Ipsum Generator
    Route::get('/lorem', [ToolsController::class, 'lorem'])->name('lorem');
    Route::post('/lorem/generate', [ToolsController::class, 'generateLorem'])
        ->name('lorem.generate')
        ->middleware('throttle:60,1');

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

    // JSON Formatter
    Route::get('/json-formatter', [ToolsController::class, 'jsonFormatter'])->name('json-formatter');

    // Cron Explainer
    Route::get('/cron', [ToolsController::class, 'cron'])->name('cron');

    // Markdown Preview
    Route::get('/markdown', [ToolsController::class, 'markdown'])->name('markdown');

    // Horário Mundial
    Route::get('/world-clock', [ToolsController::class, 'worldClock'])->name('world-clock');
    Route::get('/world-clock/search', [WorldClockController::class, 'search'])
        ->name('world-clock.search')
        ->middleware('throttle:30,1');

    // Testador de Teclado
    Route::get('/keyboard-tester', [ToolsController::class, 'keyboardTester'])->name('keyboard-tester');

    // Gerador de Senhas
    Route::get('/password-generator', [ToolsController::class, 'passwordGenerator'])->name('password-generator');

    // Seletor de Cores
    Route::get('/color-picker', [ToolsController::class, 'colorPicker'])->name('color-picker');
});
