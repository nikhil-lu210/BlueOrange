<?php

namespace App\Models\Vault\Mutators;

use Stevebauman\Purify\Facades\Purify;

trait VaultMutators
{
    /**
     * Mutator for note (Sanitize HTML before storing)
     */
    public function setNoteAttribute($value): void
    {
        $this->attributes['note'] = Purify::clean($value);
    }

    /**
     * Mutator for encrypting the username.
     */
    public function setUsernameAttribute($value)
    {
        $this->attributes['username'] = encrypt($value);
    }

    /**
     * Mutator for encrypting the password.
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = encrypt($value);
    }
}
