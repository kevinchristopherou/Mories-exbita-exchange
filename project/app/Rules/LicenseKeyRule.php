<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class LicenseKeyRule implements Rule
{
    public $message = 'License key is wrong or expired';

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $domain = request()->getHttpHost();
        $clientip = request()->getClientIp();
        $serverip = request()->server('SERVER_ADDR');

        $response = json_decode(file_get_contents(config("settings.LICENSE_VALIDATOR") . "/wp-json/envato-license/validate/{$clientip}/{$serverip}/{$domain}/{$value}/production"));

        if($response && $response->status && $response->status == "success") {
            return true;
        } else {
            $this->message = $response->message;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
