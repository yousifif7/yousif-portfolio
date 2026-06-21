<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ValidatesAntiSpam;
use App\Rules\NotSpamContent;
use App\Support\HireFormOptions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HireFormRequest extends FormRequest
{
    use ValidatesAntiSpam;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $countryCodes = collect(HireFormOptions::countryCodes())->pluck('code')->all();
        $engagementKeys = array_keys(HireFormOptions::engagementModels());
        $phaseKeys = array_keys(HireFormOptions::projectPhases());

        return array_merge($this->antiSpamRules(), [
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:191'],
            'whatsapp_country_code' => ['required', 'string', Rule::in($countryCodes)],
            'whatsapp_number' => ['required', 'string', 'regex:/^[0-9]{6,15}$/'],
            'offerings' => ['required', 'array', 'min:1'],
            'offerings.*' => ['integer', 'exists:development_offerings,id'],
            'engagement_models' => ['nullable', 'array'],
            'engagement_models.*' => [Rule::in($engagementKeys)],
            'project_phases' => ['nullable', 'array'],
            'project_phases.*' => [Rule::in($phaseKeys)],
            'message' => ['nullable', 'string', 'max:5000', new NotSpamContent],
            'attachment' => ['nullable', 'file', 'max:10240', 'mimes:pdf,doc,docx,txt,zip,rar,png,jpg,jpeg'],
            'terms_agreed' => ['accepted'],
        ]);
    }

    public function messages(): array
    {
        return array_merge($this->antiSpamMessages(), [
            'offerings.required' => 'Please select at least one type of development you are interested in.',
            'offerings.min' => 'Please select at least one type of development you are interested in.',
            'whatsapp_number.regex' => 'Please enter a valid WhatsApp number (digits only, 6–15 characters).',
            'terms_agreed.accepted' => 'You must agree to the terms before submitting.',
        ]);
    }
}
