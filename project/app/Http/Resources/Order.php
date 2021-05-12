<?php

namespace App\Http\Resources;

use App\Helpers\MoneyHelper;
use App\Models\Market;
use App\Models\Order as OrderModel;
use App\Models\Transaction;
use Illuminate\Http\Resources\Json\Resource;

class Order extends Resource
{
    protected $withoutFields = [];

    protected $method;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        switch ($this->method) {
            case 'orderbook':
                return $this->toArrayOrderBook();
            case 'myorders':
                return $this->toArrayMyOrders();
            default:
                return $this->methodDefault();
        }
    }

    public function methodDefault()
    {
        return $this->filterFields([
            'order_uuid' => $this->uuid,
            'market_id' => $this->market_id,
            'market' => $this->market->name,
            'type' => $this->type,
            'quantity' => $this->quantity_decimal,
            'rate' => $this->rate_decimal,
            'rate_actual' => $this->rate_actual_decimal,
            'quantity_remaining' => $this->quantity_remaining_decimal,
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

    /**
     * Select requested method.
     *
     * @param array $fields
     * @return $this
     */
    public function setMethod($name)
    {
        $this->method = $name;

        return $this;
    }

    // Get the list of orders by selected market
    public static function getOrderBook($market_id, $type)
    {
        $response = [
            'success' => true,
            'data' => [],
        ];

        // Order type: buy
        if ($type == 'buy' || $type == 'both') {
            $orders = OrderModel::where('type', 'BUY_LIMIT')
                ->where('market_id', $market_id)
                ->orderBy('rate', 'DESC')
                ->active()->get();
            $response['data']['buy'] = OrderCollection::collection($orders)
                ->only(['quantity', 'rate', 'uuid'])
                ->setMethod('orderbook');
        }

        // Order type: sell
        if ($type == 'sell' || $type == 'both') {
            $orders = OrderModel::where('type', 'SELL_LIMIT')
                ->where('market_id', $market_id)
                ->orderBy('rate', 'ASC')
                ->active()->get();
            $response['data']['sell'] = OrderCollection::collection($orders)
                ->only(['quantity', 'rate', 'uuid'])
                ->setMethod('orderbook');
        }

        return $response;
    }
}
