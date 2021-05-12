<?php

namespace App\Http\Resources;

use Caffeinated\Shinobi\Models\Role;
use Illuminate\Http\Resources\Json\Resource;

class User extends Resource
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
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status,
            'role' => $this->getGroups($this),
            'is_blocked' => $this->is_blocked,
            'id_verified' => $this->id_verified,
            'created_at' => $this->created_at,
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
        ];
    }
}
