<?php

namespace App\Models;

use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PermissionModule extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, HasCustomRouteId;
    
    protected $cascadeDeletes = ['permissions'];
    protected $dates = ['deleted_at'];

    protected $fillable = ['name'];


    /**
     * Get the permissions for the module.
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class, 'permission_module_id');
    }
}
