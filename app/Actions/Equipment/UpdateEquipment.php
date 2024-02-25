<?php

namespace App\Actions\Equipment;

use App\Actions\General\SyncMedia;
use App\Actions\General\SyncToPivot;
use App\Models\Equipment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UpdateEquipment
{
	public function execute(Equipment $equipment, array $request): Equipment
	{
		$this->validate($request, $equipment);

		$categories = $request['categories'] ?? null;
		$images = $request['images'] ?? null;
		unset($request['categories']);
		unset($request['images']);

		$equipment->update([
			...$request,
			'slug' => Str::slug($request['slug']),
			'last_updated_by_user_id' => auth()->user()->id,
		]);

		// remove the images if its empty (may have no images to remove)
		if (empty($images)) {
			$equipment->clearMediaCollection('images');
		}

		// link categories to this equipment
		if (isset($categories) && !is_null($categories) && is_array($categories)) {
			(new SyncToPivot())->addData($categories, $equipment, 'categories');
		}

		if (!empty($images) && is_array($images)) {
			$currentMedia = $equipment->getMedia('images');
			$uuids = collect($images)->pluck('meta.uuid')->filter()->toArray();

			// remove media thats not in the incoming images array
			$currentMedia->each(function ($media) use ($uuids) {
				if (!in_array($media->uuid, $uuids)) {
					$media->delete();
				}
			});

			foreach ($images as $image) {
				// check if data is set and its an instance of UploadedFile
				if (isset($image['data']) && $image['data'] instanceof UploadedFile) {
					// its a new image
					(new SyncMedia())->execute($equipment, $image['data'], 'images');
				}
			}
		}

		return tap($equipment)->refresh();
	}

	private function validate(array $request, Equipment $equipment): array
	{
		return Validator::validate($request, [
			'location_id' => 'sometimes|required|exists:locations,id',
			'name' => 'sometimes|required|max:255',
			'slug' => ['sometimes', 'required', Rule::unique('equipments', 'slug')->ignore($equipment->id), 'max:255'],
			'code' => ['sometimes', 'nullable', Rule::unique('equipments', 'code')->ignore($equipment->id), 'max:255'],
			'description' => 'sometimes|nullable',
			'price' => 'sometimes|required|numeric|min:0',
			'details' => 'sometimes|nullable|array',
			'amount' => 'sometimes|required|integer',
			'categories' => 'sometimes|array',
		]);
	}
}
