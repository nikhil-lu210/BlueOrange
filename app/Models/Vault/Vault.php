<?php

namespace App\Models\Vault;

use App\Models\Vault\Accessors\VaultAccessors;
use App\Models\Vault\Mutators\VaultMutators;
use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stevebauman\Purify\Casts\PurifyHtmlOnGet;
use App\Models\Vault\Relations\VaultRelations;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vault extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId;

    // Relations 
    use VaultRelations;

    // Accessors & Mutators
    use VaultAccessors, VaultMutators;
    
    protected $cascadeDeletes = ['viewers'];

    protected $casts = [
        'note' => PurifyHtmlOnGet::class
    ];

    protected $fillable = [
        'creator_id',
        'name',
        'url',
        'username',
        'password',
        'note',
    ];
}
