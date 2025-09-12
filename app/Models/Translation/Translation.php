<?php

namespace App\Models\Translation;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{

    protected $fillable = ['source_text', 'locale', 'translated_text'];
}