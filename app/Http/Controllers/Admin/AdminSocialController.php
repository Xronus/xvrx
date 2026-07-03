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
        $request->validate($this->rules());

        SocialLink::create([
            'name' => $request->name,
            'link' => $request->link,
            'class' => $request->class,
            'icon' => $request->class,
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
        $request->validate($this->rules());

        $social->update([
            'name' => $request->name,
            'link' => $request->link,
            'class' => $request->class,
            'icon' => $request->class,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.socials.index')->with('success', 'Соц.сеть успешно обновлена');
    }

    public function toggle(SocialLink $social)
    {
        $social->update(['is_active' => ! $social->is_active]);

        return redirect()->route('admin.socials.index')->with('success', ($social->is_active ? 'Включено' : 'Отключено').': '.$social->name);
    }

    public function destroy(SocialLink $social)
    {
        $social->delete();

        return redirect()->route('admin.socials.index')->with('success', 'Соц.сеть успешно удалена');
    }

    private function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'link' => [
                'required',
                'string',
                'max:255',
                function (string $attribute, mixed $value, \Closure $fail) {
                    if ($value === '#' || preg_match('/^https?:\/\//i', (string) $value)) {
                        return;
                    }

                    $fail('Ссылка должна начинаться с http://, https:// или быть временным значением #.');
                },
            ],
            'class' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
        ];
    }
}
