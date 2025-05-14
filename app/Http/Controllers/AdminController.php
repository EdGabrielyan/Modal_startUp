<?php

namespace App\Http\Controllers;

use App\Models\Domains;
use App\Notifications\DomainCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Telegram\Bot\Api;

class AdminController extends Controller
{
    public function show(int $id)
    {
        $domainPages = Domains::with('domainPages')->where('user_id', $id)->get();
        return view('admin', compact('domainPages'));
    }

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
    {
        $domainData = $request->only('domain');
        $domainData['user_id'] = auth()->id();
        $domainData['script'] = $this->randomString(32);

        // Создаем домен
        $domain = Domains::create($domainData);

        // Получаем страницы и сохраняем
        $pages = $request->input('page');
        $titles = $request->input('title');
        $descriptions = $request->input('description');

        $lastPageModel = null;

        if (is_array($pages)) {
            foreach ($pages as $index => $page) {
                $lastPageModel = $domain->domainPages()->create([
                    'page' => $page,
                    'title' => $titles[$index] ?? null,
                    'description' => $descriptions[$index] ?? null,
                ]);
            }
        } else {
            $lastPageModel = $domain->domainPages()->create([
                'page' => $pages,
                'title' => $titles,
                'description' => $descriptions,
            ]);
        }


        // Отправляем уведомление
        $contacts = $request->input('contacts', []);
        $email = $request->input('email_value');

        if (in_array('email', $contacts) && $email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Notification::route('mail', $email)->notify(new DomainCreated($domain, $lastPageModel));
        }

        $botToken = config('services.telegram.bot_token'); // or hardcode it

        $url = "https://api.telegram.org/bot{$botToken}/getUpdates";

        // Call Telegram API
        $response = Http::get($url);

        if ($response->failed()) {
            return 'Failed to fetch updates from Telegram';
        }

        $data = $response->json();

        $chatIds = [];

        if (isset($data['result'])) {
            foreach ($data['result'] as $update) {
                if (isset($update['message']['chat']['id'])) {
                    $chatIds[] = $update['message']['chat']['id'];
                }
            }
        }

        $uniqueChatIds = array_unique($chatIds);

        $telegram = new Api(config('services.telegram.bot_token'));


        $message = 'Hello from Laravel Telegram bot! 🚀';

        foreach ($uniqueChatIds as $chatId) {
            $telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => $message,
            ]);
        }

        return redirect()->route('admin.show', auth()->id());
    }


    private function randomString($length = 32): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+-=[]{}|;:<>,.?/';
        return substr(str_shuffle(str_repeat($characters, ceil($length / strlen($characters)))), 0, $length);
    }

    public function edit($id)
    {
        $domain = Domains::with('domainPages')->findOrFail($id);
        return view('edit', compact('domain'));
    }

    public function update(Request $request, $id)
    {
        $domain = Domains::findOrFail($id);

        $domain->update([
            'domain' => $request->domain,
        ]);

        $pageIds = $request->page_id ?? [];
        $existingIds = $domain->domainPages()->pluck('id')->toArray();

        $toDelete = array_diff($existingIds, $pageIds);
        if (!empty($toDelete)) {
            $domain->domainPages()->whereIn('id', $toDelete)->delete();
        }

        if (!empty($request->page)) {
            foreach ($request->page as $index => $page) {
                $title = $request->title[$index] ?? '';
                $description = $request->description[$index] ?? '';
                $pageId = $request->page_id[$index] ?? null;

                if ($pageId && in_array($pageId, $existingIds)) {
                    $domain->domainPages()->where('id', $pageId)->update([
                        'page' => $page,
                        'title' => $title,
                        'description' => $description,
                    ]);
                } else {
                    $domain->domainPages()->create([
                        'page' => $page,
                        'title' => $title,
                        'description' => $description,
                    ]);
                }
            }

        }

        return redirect()->route('admin.show', auth()->id());
    }


    public function destroy(Domains $domains): \Illuminate\Http\RedirectResponse
    {
        $domains->delete();
        return redirect()->route('admin.show', auth()->user()->id);
    }
}
