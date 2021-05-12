<?php

namespace App\Http\Requests;

use App\Helpers\SiteSettingsHelper;
use App\Models\LoginAttempt;
use App\Rules\LicenseKeyRule;
use Illuminate\Foundation\Http\FormRequest;

class LicenseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'license' => ['bail','required_without:local-testing',new LicenseKeyRule()],
            'local-testing' => ['sometimes']
        ];

        return $rules;
    }
}
