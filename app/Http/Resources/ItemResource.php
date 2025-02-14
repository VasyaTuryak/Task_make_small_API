<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'itemId' => $this->id,
            'productName' => $this->product_name,
            'quantity' => $this->quantity,
            'price' => $this->price
        ];
    }
}
