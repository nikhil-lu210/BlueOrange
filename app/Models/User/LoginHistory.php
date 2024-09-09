<?php

namespace App\Models\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoginHistory extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;
    
    protected $cascadeDeletes = [];
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id', 'login_time', 'logout_time', 'login_ip', 'logout_ip', 'user_agent',
    ];

    /**
     * Get the user for the login_histories.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
