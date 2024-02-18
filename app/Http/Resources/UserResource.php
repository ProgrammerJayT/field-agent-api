<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Crypt;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [
            'id' => Crypt::encrypt($this->user_id),
            'name' => ucwords($this->name),
            'surname' => ucwords($this->surname),
            'email' => strtolower($this->email),
            'role' => strtolower($this->role),
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }
}
