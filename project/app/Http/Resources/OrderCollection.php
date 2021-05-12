<?php

namespace App\Http\Resources;

use App\Http\Resources\Order as OrderResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrderCollection extends ResourceCollection
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
     * Send fields to hide to OrderResource while processing the collection.
     *
     * @param $request
     * @return array
     */
    protected function processCollection($request)
    {
        return $this->collection->map(function (OrderResource $resource) use ($request) {
            return $resource->only($this->withoutFields)->setMethod($this->method)->toArray($request);
        })->all();
    }

    public function with($request)
    {
        return [
            'success' => true,
        ];
    }
}
