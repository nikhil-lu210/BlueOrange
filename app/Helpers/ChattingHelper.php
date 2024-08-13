<?php

use App\Models\Chatting\Chatting;
use Illuminate\Support\Facades\Auth;

if (!function_exists('get_receiver_last_message')) {
    /**
     * Get the last message between two users.
     *
     * @param  int  $userId
     * @return Chatting|null
     */
    function get_receiver_last_message($userId)
    {
        $authUserId = Auth::id();

        return Chatting::where(function ($query) use ($userId, $authUserId) {
                    $query->where('sender_id', $userId)
                          ->where('receiver_id', $authUserId)
                          ->orWhere('sender_id', $authUserId)
                          ->where('receiver_id', $userId);
                })
                ->orderBy('created_at', 'desc')
                ->first();
    }
}


if (!function_exists('get_receiver_unread_messages_count')) {
    /**
     * Get the count of unread messages from a specific user.
     *
     * @param  int  $userId
     * @return int
     */
    function get_receiver_unread_messages_count($userId)
    {
        $authUserId = Auth::id();

        return Chatting::where('sender_id', $userId)
                       ->where('receiver_id', $authUserId)
                       ->where('seen_at', null)
                       ->count();
    }
}


if (!function_exists('get_total_unread_messages_count')) {
    /**
     * Get the count of unread messages from a specific user.
     *
     * @param  int  $userId
     * @return int
     */
    function get_total_unread_messages_count($userId = null)
    {
        if (is_null($userId)) {
            $userId = auth()->user()->id;
        }
        
        return Chatting::where('receiver_id', $userId)->where('seen_at', null)->count();
    }
}
