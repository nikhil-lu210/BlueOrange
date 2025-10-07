<?php

namespace App\Models\Translation;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Translation\Mutators\TranslationMutators;
use App\Models\Translation\Accessors\TranslationAccessors;
use App\Models\Translation\Relations\TranslationRelations;
use App\Models\Translation\Scopes\TranslationScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Observers\Administration\Translation\TranslationObserver;

#[ObservedBy([TranslationObserver::class])]
class Translation extends Model
{
    use HasFactory, SoftDeletes;

    // Relations
    use TranslationRelations;

    // Accessors & Mutators
    use TranslationAccessors, TranslationMutators;

    // Scopes
    use TranslationScopes;

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $fillable = [
        'source_text',
        'locale',
        'translated_text',
    ];
}
