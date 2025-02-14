<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'orderId' => $this->id,
            'status' => $this->status,
            'totalCost' => $this->total_amount,
            'created_at' => $this->created_at,
            'items' => ItemResource::collection($this->items),
        ];
    }
}
