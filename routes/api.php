<?php

use App\Http\Controllers\TelegramController;
use App\Models\Domains;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/widget', function (Request $request) {
    try {
        $script = $request->query('script');
        $page = $request->query('page');

        if (!$script || !$page) {
            return response()->json(['error' => 'Missing parameters'], 400)
                ->header('Access-Control-Allow-Origin', '*');
        }

        $domain = Domains::with(['domainPages' => function ($query) use ($page) {
            $query->where('page', $page);
        }])->where('script', $script)->firstOrFail();

        if (!$domain || $domain->domainPages->isEmpty()) {
            return response()->json(['error' => 'Not found'], 404)
                ->header('Access-Control-Allow-Origin', '*');
        }

        $pageData = $domain->domainPages->first();

        return response()->json([
            'title' => $pageData->title,
            'message' => $pageData->description,
            'delay' => $pageData->delay ?? 3000,
        ])->header('Access-Control-Allow-Origin', '*');
    }
    catch (ModelNotFoundException $e) {
        return response()->json([
            'message' => $e->getMessage(),
        ]);
    }
});
// routes/api.php
Route::post('/telegram/webhook', [TelegramController::class, 'webhook']);

