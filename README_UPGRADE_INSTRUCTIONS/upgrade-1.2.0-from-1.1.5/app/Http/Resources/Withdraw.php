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
        return $this->filterFields([
            'id' => $this->id,
            'amount' => $this->amount_decimal,
            'fee' => $this->fee_decimal,
            'currency' => $this->currency ? $this->currency->symbol : '',
            'status' => $this->status,
            'note' => $this->note,
            'address' => $this->address,
            'user' => $this->user,
            'created_at' => $this->created_at->toIso8601String(),
            'tx_url' => $this->getTxUrl(),
            'tx_id' => $this->getTxId(),
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
