<?php

namespace App\Models;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PermissionGroup extends Model
{
    use HasFactory;



    /**
     * Get the permissions for the blog post.
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class);
    }
}
