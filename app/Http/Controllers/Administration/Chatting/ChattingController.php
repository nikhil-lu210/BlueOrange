<?php

namespace App\Http\Controllers\Administration\Chatting;

use Auth;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Chatting\Chatting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\Chatting\ChatFileMedia;

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

        $sharedFiles = ChatFileMedia::whereHas('chatting', function($query) use ($user) {
                        $query->where(function($q) use ($user) {
                            $q->where('sender_id', auth()->id())
                              ->where('receiver_id', $user->id);
                        })->orWhere(function($q) use ($user) {
                            $q->where('sender_id', $user->id)
                              ->where('receiver_id', auth()->id());
                        });
                    })
                    ->with('chatting')
                    ->orderBy('created_at', 'desc')
                    ->get();

        // Cache key with user-specific key for uniqueness
        $userID = auth()->id();
        $cacheKey = "unread_messages_for_user_{$userID}";
        Cache::forget($cacheKey);

        return view('administration.chatting.show', compact([
            'chatUsers',
            'contacts',
            'hasChat',
            'user',
            'activeUser',
            'sharedFiles'
        ]));
    }


    /**
     * Fetch unread messages for browser notification
     */
    public function fetchUnreadMessagesForBrowser(Request $request)
    {
        $userId = auth()->id();

        // Get the current chat user ID from the request if available
        $currentChatUserId = $request->input('current_chat_user_id', null);

        // For debugging - check if we should bypass cache
        $bypassCache = $request->input('bypass_cache', false);

        // Cache key with user-specific key for uniqueness
        $cacheKey = "unread_messages_for_user_{$userId}";

        // Clear cache if requested
        if ($bypassCache) {
            Cache::forget($cacheKey);
        }

        // Get unread messages directly from the database
        $query = Chatting::where('receiver_id', $userId)
            ->whereNull('seen_at')
            ->orderBy('created_at', 'desc')
            ->with('sender.employee'); // Eager load sender

        // If we're on a specific chat page, exclude messages from that user
        if ($currentChatUserId) {
            $query->where('sender_id', '!=', $currentChatUserId);
        }

        $unreadMessages = $query->get();

        // For debugging - add total count of all unread messages
        $totalUnreadCount = Chatting::where('receiver_id', $userId)
            ->whereNull('seen_at')
            ->count();

        // Store in cache for future use
        Cache::put($cacheKey, $unreadMessages, now()->addMinutes(5));

        // Add debug info to the response
        $response = [
            'messages' => $unreadMessages,
            'debug' => [
                'total_unread_count' => $totalUnreadCount,
                'user_id' => $userId,
                'current_chat_user_id' => $currentChatUserId,
                'bypass_cache' => $bypassCache,
                'timestamp' => now()->toDateTimeString()
            ]
        ];

        return response()->json($response);
    }



    /**
     * Read Browser Notification
     */
    public function readBrowserNotification($id, $userid)
    {
        try {
            // Find the user by ID and userid
            $user = User::where('id', $id)
                ->where('userid', $userid)
                ->firstOrFail();

            // Mark messages from this user as seen
            Chatting::where('sender_id', $user->id)
                ->where('receiver_id', auth()->id())
                ->whereNull('seen_at')
                ->update(['seen_at' => now()]);

            // Clear the cache for unread messages
            $cacheKey = "unread_messages_for_user_" . auth()->id();
            Cache::forget($cacheKey);

            // Redirect to the chat page with the correct user
            return redirect()->route('administration.chatting.show', [
                'user' => $user,
                'userid' => $user->userid
            ]);
        } catch (Exception $e) {
            Log::error('Error in readBrowserNotification', [
                'error' => $e->getMessage(),
                'user_id' => $id,
                'userid' => $userid
            ]);

            // Fallback to the chat index page if there's an error
            return redirect()->route('administration.chatting.index')
                ->with('error', 'Could not open the chat. Error: ' . $e->getMessage());
        }
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
