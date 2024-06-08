<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'equipments' => EquipmentResource::collection($this->whenLoaded('equipments')),
            'equipments_count' => $this->whenCounted('equipments'),
            'name' => $this->name,
            'slug' => $this->slug,
            'image' => $this->getImage(),
            'image_src' => $this->getImage(true),
            'created_at' => $this->created_at->tz(config('app.convert_timezone'))->format(config('app.date_time_format')),
            'updated_at' => $this->updated_at->tz(config('app.convert_timezone'))->format(config('app.date_time_format')),
            'deleted_at' => $this->deleted_at?->tz(config('app.convert_timezone'))->format(config('app.date_time_format')),
        ];
    }

    /**
     * Get the image for this category including any responsive images that have been generated.
     *
     * @param  bool  $justSrc  Just return the src value for the image. Default is false.
     */
    private function getImage($justSrc = false): string|array|null
    {
        $image = $this->getMedia('image')->first();

        if ($image) {
            $image->src = $image->getSrcSet() !== '' ? $image->getSrcSet() : $image->getUrl();

            if ($justSrc) {
                return $image->src;
            }

            return $image->toArray();
        }

        // Returns null if no image found
        return $image;
    }
}
