<?php

namespace App\Models\Announcement;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use App\Models\Announcement\Traits\CommentRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnnouncementComment extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, CommentRelations;
    
    protected $cascadeDeletes = [];

    protected $fillable = [
        'announcement_id',
        'commenter_id',
        'comment'
    ];

    protected $casts = [
        'comment' => PurifyHtmlOnGet::class,
    ];
}
