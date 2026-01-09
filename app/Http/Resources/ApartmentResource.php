<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApartmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'description'      => $this->description,
            'address'          => $this->address,
            'size'             => (int) $this->size,
            'rooms'            => (int) $this->num_of_rooms,
            'price'            => (float) $this->price,
            'status'           => $this->apartment_status,
            'images' => $this->apartment_images
                ? json_decode($this->apartment_images, true)
                : [],

            'owner' => [
                'id'   => $this->owner->id,
                'name' => trim($this->owner->first_name . ' ' . $this->owner->last_name),
            ],

            'city' => [
                'id'   => $this->city->id,
                'name' => $this->city->name,
            ],
        ];
    }
}
