<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', 'email:rfc,dns', 'max:191'],
            'subject' => ['required', 'string', 'min:3', 'max:191'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
            // honeypot — must be empty
            'website' => ['nullable', 'size:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'website.size' => 'Spam detected.',
        ];
    }
}
