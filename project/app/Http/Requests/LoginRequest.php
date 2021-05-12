<?php

namespace App\Http\Requests;

use App\Helpers\SiteSettingsHelper;
use App\Models\LoginAttempt;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ];

        $captchaSiteKey = SiteSettingsHelper::get('NOCAPTCHA_SITEKEY');
        $captchaSecretKey = SiteSettingsHelper::get('NOCAPTCHA_SECRET');

        $isCaptchaInstalled = $captchaSiteKey['value'] && $captchaSecretKey['value'];

        if (LoginAttempt::isCaptchaRequired($this->ip()) && $isCaptchaInstalled) {
            $rules['g_recaptcha_response'] = 'required|captcha';
        }

        return $rules;
    }
}
