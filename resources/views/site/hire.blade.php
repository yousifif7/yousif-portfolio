@extends('site.layouts.app')

@section('title', 'Hire Me - ' . ($siteSettings['site_name'] ?? $siteAbout?->full_name ?? config('app.name')))
@section('description', 'Hire a Laravel developer for CRMs, APIs, SaaS platforms, and custom web applications.')

@push('scripts')
<script type="application/ld+json">
{!! json_encode([
    '@@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Hire Me'],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@if(!empty($faqs))
<script type="application/ld+json">
{!! json_encode([
    '@@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => collect($faqs)->map(fn ($faq) => [
        '@type' => 'Question',
        'name' => $faq['question'],
        'acceptedAnswer' => [
            '@type' => 'Answer',
            'text' => $faq['answer'],
        ],
    ])->values()->all(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endif
@endpush

@section('content')

{{-- Offerings showcase --}}
<section class="section page-hero-spacing" id="offerings">
    <div class="container">
        <div class="section-header">
            <div class="section-eyebrow">Laravel Development</div>
            <h1 class="section-title">What I Build</h1>
            <p class="section-subtitle">
                Custom web solutions powered by Laravel — from CRMs and APIs to full SaaS platforms.
                Select what you need below and tell me about your project.
            </p>
            <div class="section-header-badge">
                @include('site.partials.availability-badge')
            </div>
        </div>

        <div class="offerings-grid">
            @foreach($offerings ?? [] as $offering)
                <div class="offering-card" data-offering-id="{{ $offering->id }}">
                    <div class="offering-icon"><i class="{{ $offering->icon ?: 'fas fa-code' }}"></i></div>
                    <div class="offering-body">
                        <h3>{{ $offering->title }}</h3>
                        <p>{{ $offering->description }}</p>
                    </div>
                    <div class="offering-arrow"><i class="fas fa-chevron-right"></i></div>
                </div>
            @endforeach
        </div>
    </div>
</section>

@if(isset($featuredReview) && $featuredReview)
<section class="section" id="testimonial">
    <div class="container container-md">
        @include('site.partials.featured-review', ['review' => $featuredReview])
    </div>
</section>
@endif

@if(!empty($faqs))
<section class="section section-alt" id="faq">
    <div class="container container-md">
        <div class="section-header">
            <div class="section-eyebrow">FAQ</div>
            <h2 class="section-title">Common Questions</h2>
            <p class="section-subtitle">Quick answers before you submit your request.</p>
        </div>

        <div class="hire-faq-list">
            @foreach($faqs as $faq)
                <details class="hire-faq-item">
                    <summary>{{ $faq['question'] }}</summary>
                    <p>{{ $faq['answer'] }}</p>
                </details>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Hire form --}}
<section class="section section-alt" id="hire-form">
    <div class="container">
        <div class="hire-layout">
            <div class="hire-form-panel">
                <h2 class="hire-form-title">Hire Me</h2>
                <p class="hire-form-lead">Tell me about your project and I'll get back to you within 24–48 hours.</p>

                <div class="form-notice">
                    <i class="fas fa-info-circle"></i>
                    <span>Ready to start a project? You're in the right place. For general questions only, use the <a href="{{ route('contact') }}">Contact page</a>.</span>
                </div>

                @if($projectContext)
                    <div class="form-notice form-notice-accent">
                        <i class="fas fa-lightbulb"></i>
                        <span>Interested in something like <strong>{{ $projectContext }}</strong>? Mention it in your project details below.</span>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>Please fix the errors below.</div>
                    </div>
                @endif

                <form method="POST" action="{{ route('hire.store') }}" enctype="multipart/form-data" novalidate>
                    @csrf

                    <div class="honeypot" aria-hidden="true">
                        <label>Website</label>
                        <input type="text" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label>What are you interested in? <span class="required-mark">*</span></label>
                        <div class="checkbox-grid">
                            @foreach($offerings ?? [] as $offering)
                                <label class="checkbox-card">
                                    <input type="checkbox" name="offerings[]" value="{{ $offering->id }}"
                                        {{ in_array($offering->id, old('offerings', [])) ? 'checked' : '' }}>
                                    <span class="checkbox-card-inner">
                                        <i class="{{ $offering->icon ?: 'fas fa-code' }}"></i>
                                        {{ $offering->title }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('offerings')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label for="hire_name">Full Name <span class="required-mark">*</span></label>
                            <input type="text" id="hire_name" name="name" class="form-control" value="{{ old('name') }}" required>
                            @error('name')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label for="hire_email">Email <span class="required-mark">*</span></label>
                            <input type="email" id="hire_email" name="email" class="form-control" value="{{ old('email') }}" required>
                            @error('email')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>What engagement model do you prefer?</label>
                        <div class="checkbox-inline-group">
                            @foreach($engagementModels ?? [] as $key => $label)
                                <label class="checkbox-pill">
                                    <input type="checkbox" name="engagement_models[]" value="{{ $key }}"
                                        {{ in_array($key, old('engagement_models', [])) ? 'checked' : '' }}>
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Which phase of application development are you in?</label>
                        <div class="checkbox-inline-group">
                            @foreach($projectPhases ?? [] as $key => $label)
                                <label class="checkbox-pill">
                                    <input type="checkbox" name="project_phases[]" value="{{ $key }}"
                                        {{ in_array($key, old('project_phases', [])) ? 'checked' : '' }}>
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="whatsapp_number">WhatsApp Number <span class="required-mark">*</span></label>
                        <div class="whatsapp-input-group">
                            <select name="whatsapp_country_code" class="form-control country-select" required>
                                @foreach($countryCodes ?? [] as $country)
                                    <option value="{{ $country['code'] }}"
                                        {{ old('whatsapp_country_code', '+970') === $country['code'] ? 'selected' : '' }}>
                                        {{ $country['flag'] }} {{ $country['label'] }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="tel" id="whatsapp_number" name="whatsapp_number" class="form-control"
                                value="{{ old('whatsapp_number') }}" placeholder="599123456" pattern="[0-9]{6,15}" required>
                        </div>
                        @error('whatsapp_country_code')<div class="form-error">{{ $message }}</div>@enderror
                        @error('whatsapp_number')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="hire_message">Project Details</label>
                        <textarea id="hire_message" name="message" class="form-control" rows="4"
                            placeholder="Briefly describe your project, timeline, and any specific requirements...">{{ old('message', $projectContext ? "I'm interested in a project similar to: {$projectContext}\n\n" : '') }}</textarea>
                        @error('message')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="attachment">File Upload <span class="optional-mark">(optional)</span></label>
                        <input type="file" id="attachment" name="attachment" class="form-control file-input"
                            accept=".pdf,.doc,.docx,.txt,.zip,.rar,.png,.jpg,.jpeg">
                        <div class="form-hint">Brief, specs, wireframes — PDF, DOC, ZIP, or images up to 10 MB.</div>
                        @error('attachment')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="checkbox-terms">
                            <input type="checkbox" name="terms_agreed" value="1" {{ old('terms_agreed') ? 'checked' : '' }} required>
                            <span>I agree that my information will be used to respond to my inquiry.</span>
                        </label>
                        @error('terms_agreed')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        <i class="fas fa-paper-plane"></i> Submit Hire Request
                    </button>
                </form>
            </div>

            <aside class="hire-sidebar">
                <div class="hire-sidebar-visual">
                    <div class="hire-visual-icon"><i class="fas fa-laptop-code"></i></div>
                    <h3>Let's Build Something Great</h3>
                    <p>4+ years of Laravel experience building production-ready backends, APIs, and business applications.</p>
                </div>

                <div class="hire-sidebar-list">
                    <h4>Development Services</h4>
                    @foreach($offerings ?? [] as $offering)
                        <a href="#hire-form" class="hire-sidebar-item" data-select-offering="{{ $offering->id }}">
                            <span class="item-icon"><i class="{{ $offering->icon ?: 'fas fa-code' }}"></i></span>
                            <span class="item-text">
                                <strong>{{ $offering->title }}</strong>
                                <small>{{ Str::limit($offering->description, 60) }}</small>
                            </span>
                            <i class="fas fa-chevron-right item-arrow"></i>
                        </a>
                    @endforeach
                </div>

                <div class="hire-sidebar-trust">
                    <div class="trust-item"><i class="fas fa-shield-alt"></i> Secure &amp; confidential</div>
                    <div class="trust-item"><i class="fas fa-clock"></i> Response within 24–48h</div>
                    <div class="trust-item"><i class="fas fa-handshake"></i> Free initial consultation</div>
                </div>
            </aside>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
document.querySelectorAll('[data-select-offering]').forEach(function(link) {
    link.addEventListener('click', function(e) {
        var id = this.getAttribute('data-select-offering');
        var checkbox = document.querySelector('input[name="offerings[]"][value="' + id + '"]');
        if (checkbox) {
            checkbox.checked = true;
            checkbox.closest('.checkbox-card')?.classList.add('is-checked');
        }
    });
});

document.querySelectorAll('.checkbox-card input[type="checkbox"]').forEach(function(input) {
    function sync() {
        input.closest('.checkbox-card')?.classList.toggle('is-checked', input.checked);
    }
    input.addEventListener('change', sync);
    sync();
});

document.querySelectorAll('.offering-card').forEach(function(card) {
    card.addEventListener('click', function() {
        var id = this.getAttribute('data-offering-id');
        var checkbox = document.querySelector('input[name="offerings[]"][value="' + id + '"]');
        if (checkbox) {
            checkbox.checked = !checkbox.checked;
            checkbox.dispatchEvent(new Event('change'));
            document.getElementById('hire-form')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
});
</script>
@endpush
