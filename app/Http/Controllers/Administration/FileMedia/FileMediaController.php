<?php

namespace App\Http\Controllers\Administration\FileMedia;

use Exception;
use Illuminate\Http\Request;
use App\Models\FileMedia\FileMedia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class FileMediaController extends Controller
{
    /**
     * Handle the file download request.
     *
     * @param  FileMedia  $fileMedia
     * @return \Illuminate\Http\Response
     */
    public function download(FileMedia $fileMedia)
    {
        if (!Storage::exists($fileMedia->file_path)) {
            abort(404);
        }

        return Storage::download($fileMedia->file_path, $fileMedia->original_name);
    }

    /**
     * Soft delete the specified file.
     *
     * @param  FileMedia  $fileMedia
     * @return \Illuminate\Http\Response
     */
    public function destroy(FileMedia $fileMedia)
    {
        try {
            $fileMedia->delete();
            
            toast('The File Has Been Delete Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }


    /**
     * Handle the file download request for Spatie Media Library.
     *
     * @param  int  $mediaId
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function downloadSpatieMedia(Media $media)
    {
        try {
            // Check if the file exists on the disk
            if (!file_exists($media->getPath())) {
                return response()->json([
                    'message' => 'File not found.',
                ], 404);
            }

            // Return the file as a downloadable response
            return response()->download($media->getPath(), $media->file_name);
        } catch (Exception $e) {
            // Handle any unexpected errors
            return response()->json([
                'message' => 'An error occurred while processing the download.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
