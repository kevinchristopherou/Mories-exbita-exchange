<?php

namespace App\Http\Requests;

use App\Helpers\SiteSettingsHelper;
use App\Models\LoginAttempt;
use App\Rules\MarketStoreRule;
use Illuminate\Foundation\Http\FormRequest;

class MarketRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:20', new MarketStoreRule()],
            'min_trade_size' => 'required|numeric|min:0|not_in:0',
            'min_trade_size_quote' => 'required|numeric|min:0|not_in:0',
            'currency_format_decimals' => 'required|integer|min:0|not_in:0|max:8',
            'quote_currency_format_decimals' => 'required|integer|min:0|not_in:0|max:8',
        ];

        return $rules;
    }
}
