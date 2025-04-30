<?php

namespace App\Http\Controllers;

use App\Models\DomainPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function show(int $id)
    {
        $domainPages = DomainPage::where('user_id', $id)->get();
        return view('admin', compact('domainPages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'domain' => 'required',
            'page' => 'required',
            'script' => 'nullable',
        ]);

        $data = $request->only('domain', 'page', 'title', 'description');
        $data['script'] = Str::random();
        $data['user_id'] = auth()->user()->id;

        DomainPage::create($data);
        return redirect()->route('admin.show', auth()->user()->id);

    }

    public function update(Request $request, DomainPage $domainPage)
    {
        $request->validate([
            'domain' => 'required',
            'page' => 'required',
            'script' => 'nullable',
        ]);

        $domainPage->update($request->only('domain', 'page', 'script'));
        return redirect()->route('admin.show', auth()->user()->id);
    }

    public function destroy(DomainPage $domainPage)
    {
        $domainPage->delete();
        return redirect()->route('admin.show', auth()->user()->id);
    }
}
