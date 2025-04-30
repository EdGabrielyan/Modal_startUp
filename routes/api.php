<?php

use App\Models\DomainPage;
use Illuminate\Http\Request;

Route::get('/widget', function (Request $request) {
    $script = $request->query('script');

    $data = DomainPage::where('script', $script)->first();

    return response()->json([
        'title' => $data->title,
        'message' => $data->description,
        'delay' => 3000,
    ])->header('Access-Control-Allow-Origin', '*');
});
