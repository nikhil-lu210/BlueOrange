<?php

namespace App\Models\Comment;

use App\Models\Comment\Mutators\CommentMutators;
use App\Models\Comment\Accessors\CommentAccessors;
use App\Models\Comment\Relations\CommentRelations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    // Relations
    use CommentRelations;

    // Accessors & Mutators
    use CommentAccessors, CommentMutators;

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
