<?php

namespace App\Models\Shortcut;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use App\Models\Shortcut\Traits\ShortcutRelations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shortcut extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, ShortcutRelations;
    
    protected $cascadeDeletes = [];

    protected $fillable = [
        'user_id',
        'icon',
        'name',
        'url'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($shortcut) {
            if (auth()->check()) {
                $shortcut->user_id = auth()->user()->id;
            }
        });
    }
}
