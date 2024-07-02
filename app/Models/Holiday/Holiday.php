<?php

namespace App\Models\Holiday;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Holiday extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;
    
    protected $cascadeDeletes = [];

    protected $fillable = [
        'date',
        'name',
        'description',
        'status'
    ];
}
