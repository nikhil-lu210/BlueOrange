<?php

use Illuminate\Http\UploadedFile;
use App\Models\FileMedia\FileMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

if (!function_exists('store_file_media')) {
    /**
     * Store and optionally compress an image before associating it with a model.
     *
     * @param UploadedFile $file
     * @param Model $model
     * @param string $directory
     * @param string|null $note
     * @return FileMedia
     */
    function store_file_media(UploadedFile $file, Model $model, string $directory, string $note = null): FileMedia
    {
        $mime = $file->getMimeType();

        // Check if file is an image
        if (str_starts_with($mime, 'image/')) {
            // Compress & optimize the image
            $optimizedImage = optimize_image_before_save($file, $directory);
            $path = $optimizedImage['path'];
            $finalFileName = $optimizedImage['name'];
            $fileSize = filesize(storage_path('app/' . $path));
        } else {
            // Non-image files stored normally
            $path = $file->store($directory);
            $finalFileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
        }

        // Save FileMedia record
        $fileMedia = new FileMedia([
            'file_name' => $finalFileName,
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'file_size' => $fileSize,
            'original_name' => $file->getClientOriginalName(),
            'note' => $note,
        ]);

        $model->files()->save($fileMedia);

        return $fileMedia;
    }
}

/**
 * Optimize an image using GD before saving.
 *
 * @param UploadedFile $file
 * @param string $directory
 * @return array
 */
if (!function_exists('optimize_image_before_save')) {
    function optimize_image_before_save(UploadedFile $file, string $directory): array
    {
        $mime = $file->getMimeType();
        $sourcePath = $file->getPathname();

        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $image = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $image = imagecreatefrompng($sourcePath);
                break;
            case 'image/webp':
                $image = imagecreatefromwebp($sourcePath);
                break;
            default:
                // fallback to normal store if unsupported type
                return ['path' => $file->store($directory), 'name' => $file->getClientOriginalName()];
        }

        // Resize if too large (optional)
        $maxWidth = 1600;
        $width = imagesx($image);
        $height = imagesy($image);

        if ($width > $maxWidth) {
            $ratio = $maxWidth / $width;
            $newWidth = $maxWidth;
            $newHeight = (int)($height * $ratio);

            $resized = imagecreatetruecolor($newWidth, $newHeight);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        // Save optimized image (JPEG format for smaller size)
        $filename = uniqid('img_') . '.jpg';
        $savePath = storage_path('app/' . $directory . '/' . $filename);

        // Ensure directory exists
        if (!file_exists(dirname($savePath))) {
            mkdir(dirname($savePath), 0775, true);
        }

        imagejpeg($image, $savePath, 75); // 75 = compression level
        imagedestroy($image);

        return ['path' => $directory . '/' . $filename, 'name' => $filename];
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


if (!function_exists('spatie_media_download')) {
    /**
     * Generate the download URL for a given media item.
     *
     * @param  \Spatie\MediaLibrary\MediaCollections\Models\Media  $media
     * @return string
     */
    function spatie_media_download(Media $media)
    {
        return route('administration.file.download.spatie', ['media' => $media]);
    }
}
