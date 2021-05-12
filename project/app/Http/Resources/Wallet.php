<?php

namespace App\Http\Resources;

use App\Helpers\MoneyHelper;
use Illuminate\Http\Resources\Json\Resource;

class Wallet extends Resource
{
    protected $withoutFields = [];

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $response = [
            'id' => $this->id,
            'currency_id' => $this->currency_id,
            'currency' => $this->currency->symbol,
            'balance' => MoneyHelper::toDecimal($this->available_balance->add($this->pending_balance), $this->currency->decimals),
            'available' => $this->available_balance_decimal,
            'pending' => $this->pending_balance_decimal,
            'withdraw_pending' => $this->withdraw_pending_balance_decimal,
            'total_balance' => MoneyHelper::toDecimal($this->available_balance->add($this->pending_balance)->add($this->withdraw_pending_balance), $this->currency->decimals),
        ];

        if ($this->cryptoAddress) {
            $response['crypto_address'] = $this->cryptoAddress->address;
            $response['dest_tag'] = $this->cryptoAddress->dest_tag;
        }

        return $this->filterFields($response);
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
