<?php

namespace App\Models\Settings\Accessors;

trait SettingAccessors
{
    /**
     * Accessor for the 'value' attribute.
     *
     * @param  mixed  $value
     * @return mixed
     */
    public function getValueAttribute($value)
    {
        // If the key is 'mobile_restriction' or 'computer_restriction', return as boolean
        if (in_array($this->key, ['mobile_restriction', 'computer_restriction'])) {
            return (bool) $value;
        }

        return $value;
    }
}
