<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Realm;
use Illuminate\Http\Request;

class AdminRealmController extends Controller
{
    public function index()
    {
        $realms = Realm::orderBy('id')->get();

        return view('admin.realms.index', compact('realms'));
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
        ]);

        $data = $request->only([
            'name', 'name_en', 'name_de', 'name_es', 'name_fr',
            'rate', 'version',
            'description', 'description_en', 'description_de', 'description_es', 'description_fr',
            'proffesion', 'gold', 'rep', 'loot', 'honor',
        ]);

        Realm::create($data);

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
        ]);

        $data = $request->only([
            'name', 'name_en', 'name_de', 'name_es', 'name_fr',
            'rate', 'version',
            'description', 'description_en', 'description_de', 'description_es', 'description_fr',
            'proffesion', 'gold', 'rep', 'loot', 'honor',
        ]);

        $realm->update($data);

        return redirect()->route('admin.realms.index')->with('success', 'Реалм успешно обновлён');
    }

    public function destroy(Realm $realm)
    {
        $realm->delete();

        return redirect()->route('admin.realms.index')->with('success', 'Реалм успешно удалён');
    }
}
