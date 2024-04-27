<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
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
            'equipments' => EquipmentResource::collection($this->whenLoaded('equipment')),
            'equipments_count' => $this->whenCounted('equipments'),
            'name' => $this->name,
            'room_code' => $this->room_code,
            'created_at' => $this->created_at->tz(config('app.convert_timezone'))->format(config('app.date_time_format')),
            'updated_at' => $this->updated_at->tz(config('app.convert_timezone'))->format(config('app.date_time_format')),
            'deleted_at' => $this->deleted_at?->tz(config('app.convert_timezone'))->format(config('app.date_time_format')),
        ];
    }
}
