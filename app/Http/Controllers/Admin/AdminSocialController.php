<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use Illuminate\Http\Request;

class AdminSocialController extends Controller
{
    public function index()
    {
        $socials = SocialLink::orderBy('id')->get();

        return view('admin.socials.index', compact('socials'));
    }

    public function create()
    {
        return view('admin.socials.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'link' => 'required|url|max:255',
            'class' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        SocialLink::create([
            'name' => $request->name,
            'link' => $request->link,
            'class' => $request->class,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.socials.index')->with('success', 'Соц.сеть успешно добавлена');
    }

    public function edit(SocialLink $social)
    {
        return view('admin.socials.edit', compact('social'));
    }

    public function update(Request $request, SocialLink $social)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'link' => 'required|url|max:255',
            'class' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        $social->update([
            'name' => $request->name,
            'link' => $request->link,
            'class' => $request->class,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.socials.index')->with('success', 'Соц.сеть успешно обновлена');
    }

    public function toggle(SocialLink $social)
    {
        $social->update(['is_active' => !$social->is_active]);

        return redirect()->route('admin.socials.index')->with('success', ($social->is_active ? 'Включено' : 'Отключено') . ': ' . $social->name);
    }

    public function destroy(SocialLink $social)
    {
        $social->delete();

        return redirect()->route('admin.socials.index')->with('success', 'Соц.сеть успешно удалена');
    }
}
