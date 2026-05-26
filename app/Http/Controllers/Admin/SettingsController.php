<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\PublicUpload;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    private const SCHEMA = [
        // Identity
        'site_name' => ['group' => 'identity', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:191']],
        'site_tagline' => ['group' => 'identity', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:255']],
        'site_logo' => ['group' => 'identity', 'type' => 'image', 'rules' => ['nullable', 'file', 'mimetypes:image/jpeg,image/png,image/webp,image/svg+xml,image/svg', 'max:2048']],
        'site_favicon' => ['group' => 'identity', 'type' => 'image', 'rules' => ['nullable', 'file', 'mimetypes:image/png,image/x-icon,image/vnd.microsoft.icon,image/svg+xml,image/svg', 'max:1024']],

        // Brand colors
        'color_primary' => ['group' => 'colors', 'type' => 'color', 'rules' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/']],
        'color_accent' => ['group' => 'colors', 'type' => 'color', 'rules' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/']],
        'color_dark' => ['group' => 'colors', 'type' => 'color', 'rules' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/']],
        'color_light' => ['group' => 'colors', 'type' => 'color', 'rules' => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/']],

        // Hero overrides
        'hero_greeting' => ['group' => 'hero', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:100']],
        'hero_headline' => ['group' => 'hero', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:191']],
        'hero_subheadline' => ['group' => 'hero', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:500']],
        'hero_cta_text' => ['group' => 'hero', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:50']],
        'hero_cta_url' => ['group' => 'hero', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:500']],

        // SEO
        'seo_meta_title' => ['group' => 'seo', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:191']],
        'seo_meta_description' => ['group' => 'seo', 'type' => 'text', 'rules' => ['nullable', 'string', 'max:300']],
        'seo_og_image' => ['group' => 'seo', 'type' => 'image', 'rules' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096']],

        // Social
        'github_url' => ['group' => 'links', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:255']],
        'linkedin_url' => ['group' => 'links', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:255']],
        'twitter_url' => ['group' => 'links', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:255']],
        'facebook_url' => ['group' => 'links', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:255']],
        'instagram_url' => ['group' => 'links', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:255']],
        'stackoverflow_url' => ['group' => 'links', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:255']],

        // Freelance platforms
        'upwork_url' => ['group' => 'links', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:255']],
        'fiverr_url' => ['group' => 'links', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:255']],
        'freelancer_url' => ['group' => 'links', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:255']],
        'mostaql_url' => ['group' => 'links', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:255']],
        'khamsat_url' => ['group' => 'links', 'type' => 'url', 'rules' => ['nullable', 'url', 'max:255']],
    ];

    public function edit()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $rules = [];
        foreach (self::SCHEMA as $key => $meta) {
            $rules[$key] = $meta['rules'];
        }
        $validated = $request->validate($rules);

        foreach (self::SCHEMA as $key => $meta) {
            if ($meta['type'] === 'image') {
                $this->handleImage($request, $key, $meta);
                continue;
            }

            $value = $validated[$key] ?? null;
            Setting::set($key, $value, $meta['type'], $meta['group']);
        }

        return redirect()->route('admin.settings.edit')
            ->with('success', 'Settings updated.');
    }

    private function handleImage(Request $request, string $key, array $meta): void
    {
        $clearKey = $key.'_clear';
        $existing = Setting::get($key);

        if ($request->boolean($clearKey)) {
            PublicUpload::delete($existing);
            Setting::set($key, null, $meta['type'], $meta['group']);
            return;
        }

        if ($request->hasFile($key)) {
            PublicUpload::delete($existing);
            $path = PublicUpload::store($request->file($key), 'settings');
            Setting::set($key, $path, $meta['type'], $meta['group']);
        }
    }
}
