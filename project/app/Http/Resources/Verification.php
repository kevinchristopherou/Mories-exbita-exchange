<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Verification extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'address' => $this->address,
            'ssid' => $this->ssid,
            'name' => $this->name,
            'user' => $this->user,
            'evaluator' => $this->evaluator,
            'identity_photo' => $this->identityPhoto,
            'selfie_photo' => $this->selfiePhoto,
            'address_photo' => $this->addressPhoto,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'evaluated_at' => $this->evaluated_at,
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
        ];
    }
}
