<?php

namespace App\Actions\Location;

use App\Models\Location;
use Illuminate\Support\Facades\Validator;

class CreateLocation
{
    public function execute(array $request): Location
    {
        $this->validate($request);

        $location = Location::create([
            ...$request,
            'created_by_user_id' => auth()->user()->id,
            'last_updated_by_user_id' => auth()->user()->id,
        ]);

        return tap($location)->refresh();
    }

    private function validate(array $request): array
    {
        return Validator::validate($request, [
            'name' => 'required|max:255',
            'room_code' => 'nullable|max:255',
        ]);
    }
}
