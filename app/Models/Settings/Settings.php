<?php

namespace App\Models\Settings;

use App\Traits\HasCustomRouteId;
use Illuminate\Database\Eloquent\Model;
use App\Models\Settings\Mutators\SettingMutators;
use App\Models\Settings\Accessors\SettingAccessors;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Settings extends Model
{
    use HasFactory, HasCustomRouteId;

    // Accessors & Mutators
    use SettingAccessors, SettingMutators;

    protected $table = 'settings';

    protected $fillable = [
        'key',
        'value',
    ];
}
