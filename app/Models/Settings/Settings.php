<?php

namespace App\Models\Settings;

use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Settings extends Model
{
    use HasFactory, HasCustomRouteId;

    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
    ];
}
