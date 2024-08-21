<?php

namespace App\Models\Announcement;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\Announcement\Traits\Relations;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Announcement extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes, Relations;
    
    protected $cascadeDeletes = ['comments'];

    protected $fillable = [
        'announcer_id',
        'recipients',
        'title',
        'description',
        'read_by_at',
    ];

    protected $casts = [
        'recipients' => 'array',
        'read_by_at' => 'array',
    ];

    protected $with = ['announcer'];

    /**
     * Determine if the authenticated user is authorized to view this announcement.
     *
     * @return bool
     */
    public function isAuthorized()
    {
        $user = Auth::user();

        // If recipients is null, everyone is authorized to view
        if (is_null($this->recipients)) {
            return true;
        }

        // Ensure recipients is always an array
        $recipients = is_array($this->recipients) ? $this->recipients : json_decode($this->recipients, true);

        // Check if the user is the announcer or one of the recipients
        if ($this->announcer_id == $user->id || in_array($user->id, $recipients)) {
            return true;
        }

        return false;
    }
}
