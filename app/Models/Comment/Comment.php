<?php

namespace App\Models\Comment;

use App\Models\Comment\Mutators\CommentMutators;
use App\Models\Comment\Accessors\CommentAccessors;
use App\Models\Comment\Relations\CommentRelations;
use App\Traits\HasCustomRouteId;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\InteractsWithMedia;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, InteractsWithMedia, HasCustomRouteId;

    // Relations
    use CommentRelations;

    // Accessors & Mutators
    use CommentAccessors, CommentMutators;

    protected $cascadeDeletes = ['files'];

    protected $casts = [
        'comment' => PurifyHtmlOnGet::class
    ];

    protected $fillable = ['commentable_id', 'commentable_type', 'user_id', 'comment'];


    protected static function booted()
    {
        static::creating(function ($comment) {
            if (auth()->check() && is_null($comment->user_id)) {
                $comment->user_id = auth()->id();
            }
        });
    }
}
