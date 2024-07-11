<?php

namespace App\Http\Controllers\Administration\FileMedia;

use Illuminate\Http\Request;
use App\Models\FileMedia\FileMedia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

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
}
