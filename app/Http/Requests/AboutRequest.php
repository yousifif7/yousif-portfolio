<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AboutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:191'],
            'title' => ['required', 'string', 'max:191'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'short_bio' => ['required', 'string', 'max:1000'],
            'long_bio' => ['nullable', 'string', 'max:10000'],
            'years_of_experience' => ['nullable', 'integer', 'min:0', 'max:80'],
            'location' => ['nullable', 'string', 'max:191'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'education_degree' => ['nullable', 'string', 'max:191'],
            'education_university' => ['nullable', 'string', 'max:191'],
            'email' => ['nullable', 'email', 'max:191'],
            'phone' => ['nullable', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'github_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'twitter_url' => ['nullable', 'url', 'max:255'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'stackoverflow_url' => ['nullable', 'url', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'cv_file' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:8192'],
        ];
    }
}
