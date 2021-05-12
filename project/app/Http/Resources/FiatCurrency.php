<?php

namespace App\Http\Resources;

use App\Helpers\ExchangeHelper;
use Illuminate\Http\Resources\Json\Resource;

class FiatCurrency extends Resource
{
    protected $withoutFields = [];

    protected $method;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->filterFields([
            'id' => $this->id,
            'currency' => $this->symbol,
            'name' => $this->name,
            'withdraw_fee' => $this->withdraw_fee_decimal,
            'withdraw_min' => $this->withdraw_min_decimal,
            'withdraw_max_daily' => $this->withdraw_max_daily_decimal,
            'withdraw_max__monthly' => $this->withdraw_max_monthly_decimal,
            'deposit_min' => $this->deposit_min_decimal,
        ]);
    }

    public function with($request)
    {
        return [
            'success' => true,
        ];
    }

    /**
     * Get only defined fields.
     *
     * @param array $fields
     * @return $this
     */
    public function only(array $fields)
    {
        $this->withoutFields = $fields;

        return $this;
    }

    /**
     * Remove the filtered keys.
     *
     * @param $array
     * @return array
     */
    protected function filterFields($array)
    {
        if ($this->withoutFields) {
            return collect($array)->only($this->withoutFields)->toArray();
        }

        return collect($array)->toArray();
    }
}
