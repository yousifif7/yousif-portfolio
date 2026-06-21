<input type="hidden" name="form_token" value="{{ \App\Support\FormAntiSpam::formToken() }}">

@if(\App\Support\FormAntiSpam::turnstileEnabled())
    <div class="form-group turnstile-wrap">
        <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.site_key') }}"></div>
        @error('cf-turnstile-response')<div class="form-error">{{ $message }}</div>@enderror
    </div>

    @once
        @push('scripts')
            <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
        @endpush
    @endonce
@endif
