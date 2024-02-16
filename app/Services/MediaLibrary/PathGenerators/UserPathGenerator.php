<?php
namespace App\Services\MediaLibrary\PathGenerators;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class UserPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        return "users/{$media->model->userid}/{$media->collection_name}/";
    }

    public function getPathForConversions(Media $media): string
    {
        return "users/{$media->model->userid}/{$media->collection_name}/conversions/";
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return "users/{$media->model->userid}/{$media->collection_name}/responsive/";
    }
}
