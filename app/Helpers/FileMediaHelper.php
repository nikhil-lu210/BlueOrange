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


if (!function_exists('get_file_media_size')) {
    /**
     * Get the human-readable file size.
     *
     * @param FileMedia $file
     * @return string
     */
    function get_file_media_size(FileMedia $file): string
    {
        $bytes = $file->file_size;
        if ($bytes >= 1073741824) {
            $size = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $size = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $size = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $size = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $size = $bytes . ' byte';
        } else {
            $size = '0 bytes';
        }

        return $size;
    }
}



if (!function_exists('file_media_download')) {
    /**
     * Generate the download URL for a given FileMedia instance.
     *
     * @param  \App\Models\FileMedia\FileMedia  $fileMedia
     * @return string
     */
    function file_media_download(FileMedia $fileMedia)
    {
        return route('administration.file.download', ['fileMedia' => $fileMedia]);
    }
}



if (!function_exists('file_media_destroy')) {
    /**
     * Generate the URL for deleting a FileMedia instance.
     *
     * @param  \App\Models\FileMedia  $fileMedia
     * @return string
     */
    function file_media_destroy(FileMedia $fileMedia)
    {
        return route('administration.file.destroy', ['fileMedia' => $fileMedia]);
    }
}