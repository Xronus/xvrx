<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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
        $request->validate($this->rules(), $this->messages());

        SocialLink::create([
            'name' => $request->name,
            'link' => $request->link,
            'class' => $request->class,
            'icon' => $request->class,
            'is_active' => $request->boolean('is_active', true),
        ]);

        Cache::forget('homepage_socials');

        return redirect()->route('admin.socials.index')->with('success', __('main.social_added'));
    }

    public function edit(SocialLink $social)
    {
        return view('admin.socials.edit', compact('social'));
    }

    public function update(Request $request, SocialLink $social)
    {
        $request->validate($this->rules($social->id), $this->messages());

        $social->update([
            'name' => $request->name,
            'link' => $request->link,
            'class' => $request->class,
            'icon' => $request->class,
            'is_active' => $request->boolean('is_active'),
        ]);

        Cache::forget('homepage_socials');

        return redirect()->route('admin.socials.index')->with('success', __('main.social_updated'));
    }

    public function toggle(SocialLink $social)
    {
        $social->update(['is_active' => ! $social->is_active]);

        Cache::forget('homepage_socials');

        return redirect()->route('admin.socials.index')->with('success',
            ($social->is_active ? __('main.social_enabled') : __('main.social_disabled')).': '.$social->name
        );
    }

    public function destroy(SocialLink $social)
    {
        $social->delete();

        Cache::forget('homepage_socials');

        return redirect()->route('admin.socials.index')->with('success', __('main.social_deleted'));
    }

    private function rules(?int $socialId = null): array
    {
        $uniqueRule = 'unique:social_link,class';
        if ($socialId !== null) {
            $uniqueRule .= ','.$socialId;
        }

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

                    $fail(__('main.social_link_invalid'));
                },
            ],
            'class' => 'required|string|max:255|'.$uniqueRule,
            'is_active' => 'nullable|boolean',
        ];
    }

    private function messages(): array
    {
        return [
            'class.unique' => __('main.social_class_unique'),
        ];
    }
}
