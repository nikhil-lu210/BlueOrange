<?php

namespace App\Http\Controllers\Administration\Chatting;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Chatting\Chatting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user, $userid)
    {
        abort_if($user->userid !== $userid, 403, 'The user not exists in the database!');

        $chatUsers = $this->chatUsers(auth()->user());

        $contacts = $this->chatContacts();

        $hasChat = true;
        
        return view('administration.chatting.show', compact(['chatUsers', 'contacts', 'hasChat', 'user']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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

        foreach ($chatUsers as $user) {
            // Get last message
            $lastMessage = Chatting::where(function ($query) use ($user, $authUser) {
                $query->where('sender_id', $user->id)
                      ->where('receiver_id', $authUser->id)
                      ->orWhere('sender_id', $authUser->id)
                      ->where('receiver_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->first();

            // Get unread message count
            $unreadMessagesCount = Chatting::where('sender_id', $user->id)
                                           ->where('receiver_id', $authUser->id)
                                           ->where('seen_at', null)
                                           ->count();

            $user->last_message = $lastMessage;
            $user->unread_messages_count = $unreadMessagesCount;
        }

        return $chatUsers;
    }

    /**
     * Get all active chat contacts
     */
    private function chatContacts() {
        return User::with(['media'])
                    ->whereStatus('Active')
                    ->where('id', '!=', auth()->user()->id)
                    ->orderBy('name', 'asc')
                    ->get();
    }
}
