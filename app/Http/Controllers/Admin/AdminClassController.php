<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CharacterClass;
use App\Models\LanguageSetting;
use Illuminate\Http\Request;

class AdminClassController extends Controller
{
    public function index()
    {
        $classes = CharacterClass::orderBy('class_id')->get();

        return view('admin.classes.index', compact('classes'));
    }

    public function create()
    {
        $enabledLangs = LanguageSetting::getActiveCodes();

        return view('admin.classes.create', compact('enabledLangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|integer|unique:character_classes,class_id',
            'name' => 'required|string|max:100',
            'name_en' => 'nullable|string|max:100',
            'name_de' => 'nullable|string|max:100',
            'name_es' => 'nullable|string|max:100',
            'name_fr' => 'nullable|string|max:100',
        ]);

        CharacterClass::create($request->only(['class_id', 'name', 'name_en', 'name_de', 'name_es', 'name_fr']));

        return redirect()->route('admin.classes.index')->with('success', __('main.class_added'));
    }

    public function edit(CharacterClass $class)
    {
        $enabledLangs = LanguageSetting::getActiveCodes();

        return view('admin.classes.edit', compact('class', 'enabledLangs'));
    }

    public function update(Request $request, CharacterClass $class)
    {
        $request->validate([
            'class_id' => 'required|integer|unique:character_classes,class_id,'.$class->id,
            'name' => 'required|string|max:100',
            'name_en' => 'nullable|string|max:100',
            'name_de' => 'nullable|string|max:100',
            'name_es' => 'nullable|string|max:100',
            'name_fr' => 'nullable|string|max:100',
        ]);

        $class->update($request->only(['class_id', 'name', 'name_en', 'name_de', 'name_es', 'name_fr']));

        return redirect()->route('admin.classes.index')->with('success', __('main.class_updated'));
    }

    public function destroy(CharacterClass $class)
    {
        $class->delete();

        return redirect()->route('admin.classes.index')->with('success', __('main.class_deleted'));
    }
}
