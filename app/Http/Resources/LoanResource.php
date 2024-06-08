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
            'reference' => $this->reference,
            'denied_reason' => $this->denied_reason,

            'approval_date' => $this->approval_date?->tz(config('app.convert_timezone'))->format(config('app.date_time_format')),
            'approval_date_no_time' => $this->approval_date?->tz(config('app.convert_timezone'))->format(config('app.date_format')),
            'approval_date_no_format' => $this->approval_date,

            'start_date' => $this->start_date->tz(config('app.convert_timezone'))->format(config('app.date_time_format')),
            'start_date_no_time' => $this->start_date->tz(config('app.convert_timezone'))->format(config('app.date_format')),
            'start_date_no_format' => $this->start_date,

            'end_date' => $this->end_date->tz(config('app.convert_timezone'))->format(config('app.date_time_format')),
            'end_date_no_time' => $this->end_date->tz(config('app.convert_timezone'))->format(config('app.date_format')),
            'end_date_no_format' => $this->end_date,

            'date_returned' => $this->date_returned?->tz(config('app.convert_timezone'))->format(config('app.date_time_format')),
            'date_returned_no_time' => $this->date_returned?->tz(config('app.convert_timezone'))->format(config('app.date_format')),
            'date_returned_no_format' => $this->date_returned,

            'date_collected' => $this->date_collected?->tz(config('app.convert_timezone'))->format(config('app.date_time_format')),
            'date_collected_no_time' => $this->date_collected?->tz(config('app.convert_timezone'))->format(config('app.date_format')),
            'date_collected_no_format' => $this->date_collected,

            'date_returned' => $this->date_returned?->tz(config('app.convert_timezone'))->format(config('app.date_time_format')),
            'date_returned_no_time' => $this->date_returned?->tz(config('app.convert_timezone'))->format(config('app.date_format')),
            'date_returned_no_format' => $this->date_returned,

            'created_at' => $this->created_at->tz(config('app.convert_timezone'))->format(config('app.date_time_format')),
            'updated_at' => $this->updated_at->tz(config('app.convert_timezone'))->format(config('app.date_time_format')),
            'deleted_at' => $this->deleted_at?->tz(config('app.convert_timezone'))->format(config('app.date_time_format')),
        ];
    }
}
