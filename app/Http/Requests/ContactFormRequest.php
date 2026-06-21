<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ValidatesAntiSpam;
use App\Rules\NotSpamContent;
use Illuminate\Foundation\Http\FormRequest;

class ContactFormRequest extends FormRequest
{
    use ValidatesAntiSpam;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return array_merge($this->antiSpamRules(), [
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', 'email:rfc,dns', 'max:191'],
            'subject' => ['required', 'string', 'min:3', 'max:191', new NotSpamContent],
            'message' => ['required', 'string', 'min:10', 'max:5000', new NotSpamContent],
        ]);
    }

    public function messages(): array
    {
        return $this->antiSpamMessages();
    }
}
