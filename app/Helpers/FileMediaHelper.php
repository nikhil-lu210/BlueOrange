<?php

use Illuminate\Http\UploadedFile;
use App\Models\FileMedia\FileMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

if (!function_exists('store_file_media')) {
    /**
     * Store a file and associate it with a model.
     *
     * @param UploadedFile $file
     * @param Model $model
     * @param string $directory
     * @return FileMedia
     */
    function store_file_media(UploadedFile $file, Model $model, string $directory): FileMedia
    {
        $path = $file->store($directory);
        $fileMedia = new FileMedia([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'original_name' => $file->getClientOriginalName(),
        ]);
        $model->files()->save($fileMedia);

        return $fileMedia;
    }
}


if (!function_exists('get_file_media_url')) {
    /**
     * Get the full public URL for a file.
     *
     * @param FileMedia $file
     * @return string|null
     */
    function get_file_media_url(FileMedia $file): ?string
    {
        if ($file->file_path) {
            $storageUrl = Storage::url($file->file_path);
            return url($storageUrl);
        }
        return null;
    }
}