<?php

namespace App\Models\FunctionalityWalkthrough;

use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FunctionalityWalkthroughStep extends Model
{
    use HasFactory, SoftDeletes, HasCustomRouteId;

    protected $fillable = [
        'walkthrough_id',
        'step_title',
        'step_description',
        'step_order',
    ];

    protected $casts = [
        'step_description' => PurifyHtmlOnGet::class,
    ];

    /**
     * Get the walkthrough that owns the step.
     */
    public function walkthrough()
    {
        return $this->belongsTo(FunctionalityWalkthrough::class, 'walkthrough_id');
    }

    /**
     * Get the files for the step.
     */
    public function files()
    {
        return $this->morphMany(\App\Models\FileMedia\FileMedia::class, 'fileable');
    }
}
