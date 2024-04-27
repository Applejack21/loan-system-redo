<?php

namespace App\Actions\General;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SyncMedia
{
    /**
     * Add media to a model.
     *
     * @param  Model  $model  The model to add media to.
     * @param  string|UploadedFile  $media  The media file to add to this model.
     * @param  string  $collection  The name of the collection to add the media to. Default is "media".
     */
    public function execute(Model $model, string|UploadedFile $media, string $collection = 'media'): void
    {
        $model->addMedia($media)->toMediaCollection($collection);
    }

    /**
     * Add media to a model, using the file path instead of an UploadedFile
     *
     * @param  Model  $model  The model to add media to.
     * @param  array  $media  The media array containing the files. Requires the array to have a "name" key with the name of the image.
     * @param  string  $collection  The name of the collection to add the media to. Default is "media".
     * @param  string  $filePath  The file path the image is stored.
     * @param  bool  $removeTempImage  Decide if the temporary image should be removed after associating to the model. Default is true.
     */
    public function addFromFile(Model $model, array $media, string $collection, string $filePath, bool $removeTempImage = true): void
    {
        foreach ($media as $image) {
            // get the file
            $file = $filePath . $image['name'];

            // use file name/extension keys otherwise attempt to get from file name
            $convertedName = $image['file_name'] ?? Str::beforeLast(Str::afterLast($image['name'], '_'), '.');
            $extension = '.' . ($image['extension'] ?? Str::afterLast($image['name'], '.'));

            // add media using converted name above
            $addMedia = $model->addMedia($file)
                ->usingName($convertedName)
                ->usingFileName($convertedName . $extension);

            // decide if the original image should stay in the file path
            if (! $removeTempImage) {
                $addMedia->preserveOriginal();
            }

            // save the media
            $addMedia->toMediaCollection($collection);
        }
    }

    /**
     * Add media to the model via a url
     *
     * @param  Model  $model  The model to add media to.
     * @param  string  $url  The URL of the image to add.
     * @param  string  $collection  The name of the collection to add the media to. Default is "media".
     */
    public function addFromUrl(Model $model, string $url, string $collection = 'media'): void
    {
        $model->addMediaFromUrl($url)->toMediaCollection($collection);
    }
}
