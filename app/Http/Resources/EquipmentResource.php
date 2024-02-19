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
            'created_by' => new UserResource($this->whenLoaded('createdBy')),
            'updated_by' => new UserResource($this->whenLoaded('updatedBy')),
            'location' => new LocationResource($this->whenLoaded('location')),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'categories_count' => $this->whenCounted('categories'),
            'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
            'price' => $this->price,
            'price_formatted' => $this->priceFormatted(),
            'details' => $this->details,
            'amount' => $this->amount,
            'amount_in_stock' => $this->calculateAmountInStock(),
            'amount_on_loan' => $this->calculateAmountOnLoan(),
            'created_at' => $this->created_at->tz(config('app.convert_timezone'))->toDateTimeString(),
            'updated_at' => $this->updated_at->tz(config('app.convert_timezone'))->toDateTimeString(),
            'deleted_at' => $this->deleted_at?->tz(config('app.convert_timezone'))->toDateTimeString(),
        ];
    }
}
