@extends('site.layouts.app')

@section('title', 'Contact - ' . ($siteSettings['site_name'] ?? $siteAbout?->full_name ?? config('app.name')))
@section('description', 'Get in touch — I\'m always open to discussing new projects, ideas, or opportunities.')

@push('scripts')
<script type="application/ld+json">
{!! json_encode([
    '@@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Contact'],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')

<section class="section page-hero-spacing">
    <div class="container">
        <div class="section-header">
            <div class="section-eyebrow">Get In Touch</div>
            <h2 class="section-title">Let's Work Together</h2>
            <p class="section-subtitle">Have a project in mind or just want to say hi? Drop me a message and I'll get back to you as soon as possible.</p>
        </div>

        <div class="contact-grid">
            <div class="contact-info-card">
                <h3>Contact Information</h3>
                <p>Feel free to reach out through any of these channels.</p>

                <ul class="contact-info-list">
                    @if($siteAbout?->email)
                        <li>
                            <div class="icon"><i class="fas fa-envelope"></i></div>
                            <div>
                                <div style="font-size: 0.8rem; opacity: 0.8;">Email</div>
                                <a href="mailto:{{ $siteAbout->email }}">{{ $siteAbout->email }}</a>
                            </div>
                        </li>
                    @endif
                    @if($siteAbout?->phone)
                        <li>
                            <div class="icon"><i class="fas fa-phone"></i></div>
                            <div>
                                <div style="font-size: 0.8rem; opacity: 0.8;">Phone</div>
                                <a href="tel:{{ $siteAbout->phone }}">{{ $siteAbout->phone }}</a>
                            </div>
                        </li>
                    @endif
                    @if($siteAbout?->whatsapp)
                        <li>
                            <div class="icon"><i class="fab fa-whatsapp"></i></div>
                            <div>
                                <div style="font-size: 0.8rem; opacity: 0.8;">WhatsApp</div>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $siteAbout->whatsapp) }}" target="_blank">{{ $siteAbout->whatsapp }}</a>
                            </div>
                        </li>
                    @endif
                    @if($siteAbout?->location)
                        <li>
                            <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div>
                                <div style="font-size: 0.8rem; opacity: 0.8;">Location</div>
                                <span>{{ $siteAbout->location }}</span>
                            </div>
                        </li>
                    @endif
                </ul>

                <div class="social-icons">
                    @include('site.partials.social-icons')
                </div>
            </div>

            <div class="contact-form-card">
                @if(session('success'))
                    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            Please fix the errors below.
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('contact.store') }}" novalidate>
                    @csrf

                    {{-- Honeypot --}}
                    <div class="honeypot" aria-hidden="true">
                        <label>Website</label>
                        <input type="text" name="website" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="form-group">
                        <label for="name">Your Name</label>
                        <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        @error('email')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="subject">Subject</label>
                        <input type="text" id="subject" name="subject" class="form-control" value="{{ old('subject') }}" required>
                        @error('subject')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" class="form-control" required>{{ old('message') }}</textarea>
                        @error('message')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg btn-block">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
