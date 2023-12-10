<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EquipmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
			'description' => $this->description,
			'price' => $this->price,
			'price_formatted' => $this->priceFormatted(),
			'details' => $this->details,
			'amount' => $this->amount,
			'amount_in_stock' => $this->calculateAmountInStock(),
            'created_by' => new UserResource($this->whenLoaded('createdBy')),
            'updated_by' => new UserResource($this->whenLoaded('updatedBy')),
            'category' => new CategoryResource($this->whenLoaded('category')),
			'location' => new LocationResource($this->whenLoaded('location')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
