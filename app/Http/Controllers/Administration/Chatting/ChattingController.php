<?php

namespace App\Http\Controllers\Administration\Chatting;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Chatting\Chatting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class ChattingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chatUsers = $this->chatUsers(auth()->user());

        $contacts = $this->chatContacts();

        $hasChat = false;

        return view('administration.chatting.index', compact(['chatUsers', 'contacts', 'hasChat']));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user, $userid)
    {
        abort_if($user->userid !== $userid, 403, 'The user not exists in the database!');

        abort_if($user->id == auth()->user()->id, 403, 'You cannot chat with yourself.');

        $chatUsers = $this->chatUsers(auth()->user());

        $contacts = $this->chatContacts();

        $hasChat = true;

        $activeUser = $user->id;

        // Mark all messages from the receiver as seen
        Chatting::where('sender_id', $user->id)
                    ->where('receiver_id', auth()->user()->id)
                    ->whereNull('seen_at')
                    ->update(['seen_at' => now()]);

        // Cache key with user-specific key for uniqueness
        $userID = auth()->id();
        $cacheKey = "unread_messages_for_user_{$userID}";
        Cache::forget($cacheKey);

        return view('administration.chatting.show', compact([
            'chatUsers',
            'contacts',
            'hasChat',
            'user',
            'activeUser'
        ]));
    }


    /**
     * Fetch unread messages for browser notification
     */
    public function fetchUnreadMessagesForBrowser()
    {
        $userId = auth()->id();

        // Cache key with user-specific key for uniqueness
        $cacheKey = "unread_messages_for_user_{$userId}";

        // Cache expiration time in seconds (e.g., 5 minutes)
        $cacheExpiration = 300; // 5 minutes

        // Try fetching from the cache, or if not found, retrieve from the database and cache it
        $unreadMessages = Cache::remember($cacheKey, $cacheExpiration, function () use ($userId) {
            return Chatting::where('receiver_id', $userId)
                ->whereNull('seen_at')
                ->orderBy('created_at', 'desc')
                ->with('sender.employee') // Eager load sender
                ->get();
        });

        return response()->json($unreadMessages);
    }



    /**
     * Read Browser Notification
     */
    public function readBrowserNotification($id, $userid)
    {
        $user = User::whereId($id)->whereUserid($userid)->firstOrFail();

        return redirect()->route('administration.chatting.show', ['user' => $user, 'userid' => $user->userid]);
    }



    /**
     * get all users with whom the auth user chatted
     */
    private function chatUsers($authUser) {
        $chatUsers = User::with(['media'])
            ->whereExists(function ($query) use ($authUser) {
                $query->select(DB::raw(1))
                    ->from('chattings')
                    ->where(function ($query) use ($authUser) {
                        $query->where('chattings.sender_id', $authUser->id)
                            ->whereColumn('chattings.receiver_id', 'users.id')
                            ->orWhere(function ($query) use ($authUser) {
                                $query->where('chattings.receiver_id', $authUser->id)
                                    ->whereColumn('chattings.sender_id', 'users.id');
                            });
                    });
            })
            ->select('users.userid', 'users.id', 'users.name')
            ->addSelect([
                'last_message_time' => Chatting::select('created_at')
                    ->where(function ($query) use ($authUser) {
                        $query->whereColumn('chattings.sender_id', 'users.id')
                            ->where('chattings.receiver_id', $authUser->id)
                            ->orWhere(function ($query) use ($authUser) {
                                $query->whereColumn('chattings.receiver_id', 'users.id')
                                    ->where('chattings.sender_id', $authUser->id);
                            });
                    })
                    ->orderByDesc('created_at')
                    ->limit(1)
            ])
            ->orderByDesc('last_message_time')
            ->get();

        return $chatUsers;
    }

    /**
     * Get all active chat contacts
     */
    private function chatContacts() {
        $chatContacts = Auth::user()->user_interactions->filter(function($user) {
            return $user->status === 'Active' && $user->id !== Auth::id();
        })->sortBy('name');

        return $chatContacts;
    }
}
