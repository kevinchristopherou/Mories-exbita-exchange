<?php

namespace App\Http\Resources;

use App\Http\Resources\FiatCurrency as FiatCurrencyResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FiatCurrencyCollection extends ResourceCollection
{
    /**
     * @var array
     */
    protected $withoutFields = [];

    /**
     * @var string
     */
    protected $method;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->processCollection($request);
    }

    public static function collection($resource)
    {
        return tap(new self($resource), function ($collection) {
            $collection->collects = __CLASS__;
        });
    }

    public function only(array $fields)
    {
        $this->withoutFields = $fields;

        return $this;
    }

    public function setMethod($name = '')
    {
        $this->method = $name;

        return $this;
    }

    /**
     * Pass only selected field to FiatCurrencyResource while processing the collection.
     *
     * @param $request
     * @return array
     */
    protected function processCollection($request)
    {
        return $this->collection->map(function (FiatCurrencyResource $resource) use ($request) {
            return $resource->only($this->withoutFields)->toArray($request);
        })->all();
    }

    public function with($request)
    {
        return [
            'success' => true,
        ];
    }
}
