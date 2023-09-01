<?php

namespace App\Models;

use App\Models\PermissionGroup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
    use HasFactory;


    /**
     * Get the permission_group that owns the comment.
     */
    public function permission_group(): BelongsTo
    {
        return $this->belongsTo(PermissionGroup::class);
    }
}
