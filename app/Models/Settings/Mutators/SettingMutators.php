<?php

namespace App\Models\Settings\Mutators;

use Stevebauman\Purify\Facades\Purify;

trait SettingMutators
{
    /**
     * Mutator for the 'value' attribute.
     *
     * @param  mixed  $value
     * @return void
     */
    public function setValueAttribute($value)
    {
        // If the key is 'mobile_restriction' or 'computer_restriction', store as boolean
        if (in_array($this->key, ['mobile_restriction', 'computer_restriction'])) {
            $this->attributes['value'] = (bool) $value;
        } else {
            $this->attributes['value'] = $value;
        }
    }
}
