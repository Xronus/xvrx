<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Race;
use App\Models\LanguageSetting;
use Illuminate\Http\Request;

class AdminRaceController extends Controller
{
    public function index()
    {
        $races = Race::orderBy('race_id')->get();

        return view('admin.races.index', compact('races'));
    }

    public function create()
    {
        $enabledLangs = LanguageSetting::getActiveCodes();
        return view('admin.races.create', compact('enabledLangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'race_id' => 'required|integer|unique:races,race_id',
            'name' => 'required|string|max:100',
            'name_en' => 'nullable|string|max:100',
            'name_de' => 'nullable|string|max:100',
            'name_es' => 'nullable|string|max:100',
            'name_fr' => 'nullable|string|max:100',
            'faction' => 'required|in:0,1',
        ]);

        Race::create($request->only(['race_id', 'name', 'name_en', 'name_de', 'name_es', 'name_fr', 'faction']));

        return redirect()->route('admin.races.index')->with('success', 'Раса успешно добавлена');
    }

    public function edit(Race $race)
    {
        $enabledLangs = LanguageSetting::getActiveCodes();
        return view('admin.races.edit', compact('race', 'enabledLangs'));
    }

    public function update(Request $request, Race $race)
    {
        $request->validate([
            'race_id' => 'required|integer|unique:races,race_id,' . $race->id,
            'name' => 'required|string|max:100',
            'name_en' => 'nullable|string|max:100',
            'name_de' => 'nullable|string|max:100',
            'name_es' => 'nullable|string|max:100',
            'name_fr' => 'nullable|string|max:100',
            'faction' => 'required|in:0,1',
        ]);

        $race->update($request->only(['race_id', 'name', 'name_en', 'name_de', 'name_es', 'name_fr', 'faction']));

        return redirect()->route('admin.races.index')->with('success', 'Раса успешно обновлена');
    }

    public function destroy(Race $race)
    {
        $race->delete();

        return redirect()->route('admin.races.index')->with('success', 'Раса успешно удалена');
    }
}
