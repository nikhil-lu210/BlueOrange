<?php

namespace App\Models\Vault\Relations;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait VaultRelations
{
    /**
     * Get the creator for the vault.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }


    /**
     * Get the viewers associated with the vault.
     */
    public function viewers(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}