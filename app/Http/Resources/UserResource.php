<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'type' => $this->type,
            'phone_number' => $this->phone_number,
            'address' => $this->address,
            'profile_photo_url' => $this->profile_photo_url,
            // TODO: add loans collection once model is done
            'created_at' => $this->created_at->tz(config('app.convert_timezone'))->toDateTimeString(),
            'updated_at' => $this->updated_at->tz(config('app.convert_timezone'))->toDateTimeString(),
            'deleted_at' => $this->deleted_at?->tz(config('app.convert_timezone'))->toDateTimeString(),
        ];
    }
}
