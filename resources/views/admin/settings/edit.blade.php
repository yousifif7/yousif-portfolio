@extends('admin.layouts.app')

@section('title', 'Site Settings')
@section('page_title', 'Site Settings')

@section('content')

@php
    $val = fn ($key, $default = '') => old($key, $settings[$key] ?? $default);
    $img = function ($key) use ($settings) {
        $path = $settings[$key] ?? null;
        return $path ? \App\Support\PublicUpload::url($path) : null;
    };
@endphp

<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')

    {{-- Identity --}}
    <div class="card">
        <div class="card-header"><h2><i class="fas fa-id-card"></i> Site Identity</h2></div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Site Name</label>
                    <input type="text" name="site_name" class="form-control" value="{{ $val('site_name') }}" placeholder="Shown in browser tab and navbar">
                    @error('site_name')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Footer Tagline</label>
                    <input type="text" name="site_tagline" class="form-control" value="{{ $val('site_tagline') }}" placeholder="Short tagline shown in the footer">
                    @error('site_tagline')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Logo</label>
                    @if($img('site_logo'))
                        <div class="image-preview"><div class="img-thumb" style="background:#f3f4f6"><img src="{{ $img('site_logo') }}" alt="Logo" style="object-fit:contain;"></div></div>
                        <label style="display:flex;align-items:center;gap:0.4rem;margin:0.4rem 0;font-size:0.85rem;">
                            <input type="checkbox" name="site_logo_clear" value="1"> Remove current logo
                        </label>
                    @endif
                    <input type="file" name="site_logo" class="form-control" accept="image/*">
                    <div class="form-text">PNG, JPG, WebP or SVG. Max 2 MB. Optional — site name will be shown when empty.</div>
                    @error('site_logo')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Favicon</label>
                    @if($img('site_favicon'))
                        <div class="image-preview"><div class="img-thumb" style="width:48px;height:48px;background:#f3f4f6"><img src="{{ $img('site_favicon') }}" alt="Favicon" style="object-fit:contain;"></div></div>
                        <label style="display:flex;align-items:center;gap:0.4rem;margin:0.4rem 0;font-size:0.85rem;">
                            <input type="checkbox" name="site_favicon_clear" value="1"> Remove current favicon
                        </label>
                    @endif
                    <input type="file" name="site_favicon" class="form-control" accept="image/png,image/x-icon,image/svg+xml">
                    <div class="form-text">PNG, ICO or SVG. Max 1 MB.</div>
                    @error('site_favicon')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>

    {{-- Brand colors --}}
    <div class="card">
        <div class="card-header"><h2><i class="fas fa-palette"></i> Brand Colors</h2></div>
        <div class="card-body">
            <div class="form-text" style="margin-bottom:1rem;">Pick HEX colors. Leave blank to use defaults from the stylesheet.</div>
            <div class="form-row">
                @foreach([
                    'color_primary' => 'Primary',
                    'color_accent' => 'Accent',
                    'color_dark' => 'Dark',
                    'color_light' => 'Light / Background',
                ] as $key => $label)
                    <div class="form-group">
                        <label class="form-label">{{ $label }}</label>
                        <div style="display:flex;gap:0.5rem;align-items:center;">
                            <input type="color" data-color-picker="{{ $key }}" value="{{ $val($key) ?: '#000000' }}" style="width:50px;height:38px;padding:2px;border:1px solid var(--gray-300);border-radius:6px;cursor:pointer;">
                            <input type="text" name="{{ $key }}" data-color-hex="{{ $key }}" value="{{ $val($key) }}" class="form-control" placeholder="#000000 (leave blank for default)" pattern="^#[0-9a-fA-F]{6}$" style="flex:1;">
                        </div>
                        @error($key)<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                @endforeach
            </div>
            <div class="form-text">
                Tip: clearing a color resets it to the stylesheet's built-in value.
            </div>
        </div>
    </div>

    {{-- Hero --}}
    <div class="card">
        <div class="card-header"><h2><i class="fas fa-bullhorn"></i> Homepage Hero Overrides</h2></div>
        <div class="card-body">
            <div class="form-text" style="margin-bottom:1rem;">Override the homepage hero. Leave blank to use defaults from your About profile.</div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Greeting</label>
                    <input type="text" name="hero_greeting" class="form-control" value="{{ $val('hero_greeting') }}" placeholder="Hello, I'm">
                </div>
                <div class="form-group">
                    <label class="form-label">Headline</label>
                    <input type="text" name="hero_headline" class="form-control" value="{{ $val('hero_headline') }}" placeholder="Leave blank to use About name">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Sub-headline</label>
                <textarea name="hero_subheadline" class="form-control" rows="2" placeholder="Leave blank to use About tagline">{{ $val('hero_subheadline') }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">CTA Button Text</label>
                    <input type="text" name="hero_cta_text" class="form-control" value="{{ $val('hero_cta_text') }}" placeholder="View Projects">
                </div>
                <div class="form-group">
                    <label class="form-label">CTA Button URL</label>
                    <input type="url" name="hero_cta_url" class="form-control" value="{{ $val('hero_cta_url') }}" placeholder="https://...">
                    @error('hero_cta_url')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>

    {{-- SEO --}}
    <div class="card">
        <div class="card-header"><h2><i class="fas fa-search"></i> SEO Defaults</h2></div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Default Meta Title</label>
                    <input type="text" name="seo_meta_title" class="form-control" value="{{ $val('seo_meta_title') }}" maxlength="191">
                </div>
                <div class="form-group">
                    <label class="form-label">Default Meta Description</label>
                    <input type="text" name="seo_meta_description" class="form-control" value="{{ $val('seo_meta_description') }}" maxlength="300">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Default OG Share Image</label>
                @if($img('seo_og_image'))
                    <div class="image-preview"><div class="img-thumb" style="width:200px;height:105px;"><img src="{{ $img('seo_og_image') }}" alt="OG image"></div></div>
                    <label style="display:flex;align-items:center;gap:0.4rem;margin:0.4rem 0;font-size:0.85rem;">
                        <input type="checkbox" name="seo_og_image_clear" value="1"> Remove current image
                    </label>
                @endif
                <input type="file" name="seo_og_image" class="form-control" accept="image/*">
                <div class="form-text">Used when sharing the site on Twitter, Facebook, LinkedIn, etc. Recommended 1200×630.</div>
                @error('seo_og_image')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Google Search Console Verification</label>
                <input type="text" name="seo_google_site_verification" class="form-control" value="{{ $val('seo_google_site_verification') }}" placeholder="Paste the content value from Google's meta tag">
                <div class="form-text">From Google Search Console → Settings → Ownership verification → HTML tag. Paste only the <code>content="..."</code> value, not the full tag.</div>
                @error('seo_google_site_verification')<div class="form-error">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    {{-- Social Links --}}
    <div class="card">
        <div class="card-header"><h2><i class="fas fa-share-alt"></i> Social & Freelance Links</h2></div>
        <div class="card-body">
            <div class="form-text" style="margin-bottom:1rem;">
                Any URL you fill here will appear as a clickable icon in the navbar, footer, and contact page. Leave blank to hide that icon.
            </div>

            <h3 style="font-size:0.95rem;margin:0.5rem 0 0.75rem;color:var(--gray-600);">Social</h3>
            <div class="form-row">
                @foreach([
                    'github_url' => ['GitHub', 'fab fa-github', 'https://github.com/...'],
                    'linkedin_url' => ['LinkedIn', 'fab fa-linkedin', 'https://linkedin.com/in/...'],
                    'twitter_url' => ['Twitter / X', 'fab fa-twitter', 'https://x.com/...'],
                    'facebook_url' => ['Facebook', 'fab fa-facebook', 'https://facebook.com/...'],
                    'instagram_url' => ['Instagram', 'fab fa-instagram', 'https://instagram.com/...'],
                    'stackoverflow_url' => ['Stack Overflow', 'fab fa-stack-overflow', 'https://stackoverflow.com/users/...'],
                ] as $key => [$label, $icon, $placeholder])
                    <div class="form-group">
                        <label class="form-label"><i class="{{ $icon }}"></i> {{ $label }}</label>
                        <input type="url" name="{{ $key }}" class="form-control" value="{{ $val($key) }}" placeholder="{{ $placeholder }}">
                        @error($key)<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                @endforeach
            </div>

            <h3 style="font-size:0.95rem;margin:1.25rem 0 0.75rem;color:var(--gray-600);">Freelance Platforms</h3>
            <div class="form-row">
                @foreach([
                    'upwork_url' => ['Upwork', 'fas fa-briefcase', 'https://upwork.com/freelancers/...'],
                    'fiverr_url' => ['Fiverr', 'fas fa-bolt', 'https://fiverr.com/...'],
                    'freelancer_url' => ['Freelancer', 'fas fa-laptop-code', 'https://freelancer.com/u/...'],
                    'mostaql_url' => ['Mostaql', 'fas fa-handshake', 'https://mostaql.com/u/...'],
                    'khamsat_url' => ['Khamsat', 'fas fa-tasks', 'https://khamsat.com/user/...'],
                ] as $key => [$label, $icon, $placeholder])
                    <div class="form-group">
                        <label class="form-label"><i class="{{ $icon }}"></i> {{ $label }}</label>
                        <input type="url" name="{{ $key }}" class="form-control" value="{{ $val($key) }}" placeholder="{{ $placeholder }}">
                        @error($key)<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div style="display:flex;gap:0.5rem;margin-bottom:2rem;">
        <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save"></i> Save Settings</button>
        <a href="{{ route('home') }}" target="_blank" class="btn btn-outline"><i class="fas fa-eye"></i> Preview Site</a>
    </div>
</form>

<script>
    document.querySelectorAll('input[data-color-picker]').forEach(picker => {
        const key = picker.dataset.colorPicker;
        const hex = document.querySelector(`input[data-color-hex="${key}"]`);
        if (!hex) return;
        picker.addEventListener('input', () => hex.value = picker.value);
        hex.addEventListener('input', () => {
            if (/^#[0-9a-fA-F]{6}$/.test(hex.value)) picker.value = hex.value;
        });
    });
</script>

@endsection
