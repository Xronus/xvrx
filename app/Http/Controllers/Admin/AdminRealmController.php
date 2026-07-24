<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Realm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminRealmController extends Controller
{
    public function index()
    {
        $realms = Realm::orderBy('id')->get();
        $canCreate = $realms->isEmpty();

        return view('admin.realms.index', compact('realms', 'canCreate'));
    }

    public function create()
    {
        return view('admin.realms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'name_de' => 'nullable|string|max:255',
            'name_es' => 'nullable|string|max:255',
            'name_fr' => 'nullable|string|max:255',
            'rate' => 'required|string|max:255',
            'version' => 'required|string|max:255',
            'full_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'description_en' => 'nullable|string|max:255',
            'description_de' => 'nullable|string|max:255',
            'description_es' => 'nullable|string|max:255',
            'description_fr' => 'nullable|string|max:255',
            'proffesion' => 'nullable|string|max:255',
            'gold' => 'nullable|string|max:255',
            'rep' => 'nullable|string|max:255',
            'loot' => 'nullable|string|max:255',
            'honor' => 'nullable|string|max:255',
            'link_url' => 'nullable|string|max:500',
            'link_text' => 'nullable|string|max:100',
        ]);

        $data = $request->only([
            'name', 'name_en', 'name_de', 'name_es', 'name_fr',
            'rate', 'version', 'full_name',
            'description', 'description_en', 'description_de', 'description_es', 'description_fr',
            'proffesion', 'gold', 'rep', 'loot', 'honor',
            'link_url', 'link_text',
        ]);

        Realm::create($data);

        Cache::forget('homepage_realms');

        return redirect()->route('admin.realms.index')->with('success', 'Реалм успешно добавлен');
    }

    public function edit(Realm $realm)
    {
        return view('admin.realms.edit', compact('realm'));
    }

    public function update(Request $request, Realm $realm)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'name_de' => 'nullable|string|max:255',
            'name_es' => 'nullable|string|max:255',
            'name_fr' => 'nullable|string|max:255',
            'rate' => 'required|string|max:255',
            'version' => 'required|string|max:255',
            'full_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:255',
            'description_en' => 'nullable|string|max:255',
            'description_de' => 'nullable|string|max:255',
            'description_es' => 'nullable|string|max:255',
            'description_fr' => 'nullable|string|max:255',
            'proffesion' => 'nullable|string|max:255',
            'gold' => 'nullable|string|max:255',
            'rep' => 'nullable|string|max:255',
            'loot' => 'nullable|string|max:255',
            'honor' => 'nullable|string|max:255',
            'link_url' => 'nullable|string|max:500',
            'link_text' => 'nullable|string|max:100',
        ]);

        $data = $request->only([
            'name', 'name_en', 'name_de', 'name_es', 'name_fr',
            'rate', 'version', 'full_name',
            'description', 'description_en', 'description_de', 'description_es', 'description_fr',
            'proffesion', 'gold', 'rep', 'loot', 'honor',
            'link_url', 'link_text',
        ]);

        $realm->update($data);

        Cache::forget('homepage_realms');

        return redirect()->route('admin.realms.index')->with('success', 'Реалм успешно обновлён');
    }

    public function destroy(Realm $realm)
    {
        $realm->delete();

        Cache::forget('homepage_realms');

        return redirect()->route('admin.realms.index')->with('success', 'Реалм успешно удалён');
    }
}
