<?php

namespace App\Models\Vault\Accessors;

trait VaultAccessors
{
    /**
     * Accessor for decrypting the username.
     */
    public function getUsernameAttribute($value)
    {
        return decrypt($value);
    }

    /**
     * Accessor for decrypting the password.
     */
    public function getPasswordAttribute($value)
    {
        return decrypt($value);
    }
}
