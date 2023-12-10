<?php

namespace App\Actions\Location;

use App\Models\Location;
use Illuminate\Support\Facades\Validator;

class UpdateLocation
{
    public function execute(Location $location, array $request): Location
    {
        $this->validate($request, $location);

        $location->update([
            ...$request,
            'created_by_user_id' => auth()->user()->id,
            'last_updated_by_user_id' => auth()->user()->id,
        ]);

        return tap($location)->refresh();
    }

    private function validate(array $request, Location $location): array
    {
        return Validator::validate($request, [
            'name' => 'required|max:255',
            'room_code' => 'nullable|max:255',
        ]);
    }
}
