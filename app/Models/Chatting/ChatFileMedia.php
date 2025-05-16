<?php

namespace App\Models\Chatting;

use App\Models\User;
use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChatFileMedia extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId;
    
    protected $cascadeDeletes = [];

    protected $fillable = [
        'chatting_id',
        'uploader_id',
        'file_name',
        'file_path',
        'mime_type',
        'file_extension',
        'file_size',
        'original_name',
        'is_image',
    ];

    protected $casts = [
        'is_image' => 'boolean',
    ];

    /**
     * Get the chatting that owns the file.
     */
    public function chatting()
    {
        return $this->belongsTo(Chatting::class);
    }

    /**
     * Get the uploader of the file.
     */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    /**
     * Get the full URL to the file.
     */
    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    /**
     * Determine if the file is an image.
     */
    public function getIsImageAttribute($value)
    {
        if ($value) {
            return $value;
        }
        
        // If not explicitly set, check the extension
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
        return in_array(strtolower($this->file_extension), $imageExtensions);
    }
}
