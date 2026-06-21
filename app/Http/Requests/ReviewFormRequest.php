<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ValidatesAntiSpam;
use App\Rules\NotSpamContent;
use Illuminate\Foundation\Http\FormRequest;

class ReviewFormRequest extends FormRequest
{
    use ValidatesAntiSpam;

    protected function getRedirectUrl(): string
    {
        return route('home').'#reviews';
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return array_merge($this->antiSpamRules(), [
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:191'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'content' => ['required', 'string', 'min:20', 'max:2000', new NotSpamContent],
            'company' => ['nullable', 'string', 'max:120'],
            'role' => ['nullable', 'string', 'max:120'],
        ]);
    }

    public function messages(): array
    {
        return $this->antiSpamMessages();
    }
}
