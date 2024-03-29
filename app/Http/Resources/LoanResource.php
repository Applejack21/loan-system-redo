<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoanResource extends JsonResource
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
			'approved_by' => new UserResource($this->whenLoaded('approvedBy')),
			'denied_by' => new UserResource($this->whenLoaded('deniedBy')),
			'loanee' => new UserResource($this->whenLoaded('loanee')),
			'equipments' => EquipmentResource::collection($this->whenLoaded('equipments')),
			'equipments_count' => $this->whenCounted('equipments'),
			'status' => $this->status,
			'approval_date' => $this->approval_date?->tz(config('app.convert_timezone'))->toDateTimeString(),
			'start_date' => $this->start_date->tz(config('app.convert_timezone'))->toDateTimeString(),
			'end_date' => $this->end_date->tz(config('app.convert_timezone'))->toDateTimeString(),
			'date_returned' => $this->date_returned?->tz(config('app.convert_timezone'))->toDateTimeString(),
			'created_at' => $this->created_at->tz(config('app.convert_timezone'))->toDateTimeString(),
			'updated_at' => $this->updated_at->tz(config('app.convert_timezone'))->toDateTimeString(),
			'deleted_at' => $this->deleted_at?->tz(config('app.convert_timezone'))->toDateTimeString(),
		];
	}
}
