<?php

namespace App\Http\Controllers;

use App\Models\Domains;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        $domainData['script'] = Str::random();
        $domain = Domains::create($domainData);

        $pages = $request->input('page');
        $titles = $request->input('title');
        $descriptions = $request->input('description');

        if (is_array($pages)) {
            foreach ($pages as $index => $page) {
                $domain->domainPages()->create([
                    'page' => $page,
                    'title' => $titles[$index] ?? null,
                    'description' => $descriptions[$index] ?? null,
                ]);
            }
        } else {
            $domain->domainPages()->create([
                'page' => $pages,
                'title' => $titles,
                'description' => $descriptions,
            ]);
        }

        return redirect()->route('admin.show', auth()->id());
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

        $pageIds = $request->page_id ?? []; // Список пришедших ID страниц
        $existingIds = $domain->domainPages()->pluck('id')->toArray(); // Список текущих ID в базе

        // Удалим те, которых больше нет
        $toDelete = array_diff($existingIds, $pageIds);
        if (!empty($toDelete)) {
            $domain->domainPages()->whereIn('id', $toDelete)->delete();
        }

        if(!empty($request->page)){
            foreach ($request->page as $index => $page) {
                $title = $request->title[$index] ?? '';
                $description = $request->description[$index] ?? '';
                $pageId = $request->page_id[$index] ?? null;

                if ($pageId && in_array($pageId, $existingIds)) {
                    // Обновляем существующую
                    $domain->domainPages()->where('id', $pageId)->update([
                        'page' => $page,
                        'title' => $title,
                        'description' => $description,
                    ]);
                } else {
                    // Создаём новую
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
