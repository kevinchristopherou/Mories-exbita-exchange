<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Withdraw extends Resource
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
        $fields = [
            'id' => $this->id,
            'amount' => $this->amount_decimal,
            'fee' => $this->fee_decimal,
            'currency' => $this->currency ? $this->currency->symbol : '',
            'status' => $this->status,
            'note' => $this->note,
            'address' => $this->address,
            'dest_tag' => $this->dest_tag,
            'user' => $this->user,
            'created_at' => $this->created_at->toIso8601String(),
            'tx_url' => $this->getTxUrl(),
            'tx_id' => $this->getTxId(),
        ];

        if (auth('api')->user() && auth('api')->user()->hasRole('admin')) {
            $fields['withdrawable'] = $this->currency && $this->currency->account_balance_decimal >= $this->amount_decimal ? true : false;
        }

        return $this->filterFields($fields);
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
