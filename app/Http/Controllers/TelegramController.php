<?php

// app/Http/Controllers/TelegramController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class TelegramController extends Controller
{
    public function webhook(Request $request)
    {
        $data = $request->all();

        $chatId = $data['message']['chat']['id'] ?? null;
        $text = $data['message']['text'] ?? null;

        if ($chatId && $text) {
            Client::updateOrCreate(
                ['telegram_chat_id' => $chatId],
                ['telegram_username' => $data['message']['chat']['username'] ?? null]
            );
        }

        return response()->json(['ok' => true]);
    }
}

