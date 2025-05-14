<?php

// app/Http/Controllers/TelegramController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TelegramUser;

class TelegramController extends Controller
{
    public function webhook(Request $request)
    {
        $data = $request->all();

        // Проверка, что сообщение пришло от Telegram
        if (isset($data['message']['chat']['id'])) {
            $chatId = $data['message']['chat']['id'];
            $userId = auth()->id(); // Получаем текущего пользователя

            // Сохраняем chat_id пользователя в базе данных
            TelegramUser::updateOrCreate(
                ['user_id' => $userId],
                ['chat_id' => $chatId]
            );

            // Отправляем подтверждение
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error'], 400);
    }
    public function setWebhook()
    {
        $token = config('services.telegram.bot_token');
        $url = route('telegram.webhook'); // Вебхук на твой роут

        $response = Http::post("https://api.telegram.org/bot{$token}/setWebhook", [
            'url' => $url
        ]);

        return $response->json();
    }
}
