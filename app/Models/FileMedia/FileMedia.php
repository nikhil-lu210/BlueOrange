<?php

namespace App\Models\FileMedia;

use Illuminate\Database\Eloquent\Model;
use App\Models\FileMedia\Traits\Relations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FileMedia extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, Relations;
    
    protected $cascadeDeletes = [];

    protected $fillable = [
        'uploader_id',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
        'original_name',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($file) {
            if (auth()->check()) {
                $file->uploader_id = auth()->user()->id;
            }
        });
    }
}
