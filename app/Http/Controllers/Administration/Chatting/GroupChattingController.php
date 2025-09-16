<?php

namespace App\Http\Controllers\Administration\Chatting;

use Auth;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\Chatting\ChattingGroup;
use App\Models\Chatting\GroupChatting;
use App\Models\Chatting\GroupChatFileMedia;

class GroupChattingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = $this->getRoleUsers();
        $chatGroups = $this->chatGroups(auth()->user());

        $hasChat = false;

        return view('administration.chatting.group.index', compact(['roles', 'chatGroups', 'hasChat']));
    }

    /**
     * Display the specified resource.
     */
    public function show(ChattingGroup $group, $groupid)
    {
        abort_if($group->groupid !== $groupid, 403, 'The Group is not exists in the database!');

        $roles = $this->getRoleUsers();

        $chatGroups = $this->chatGroups(auth()->user());

        $hasChat = true;

        $activeGroup = $group->id;

        // Get shared files for the group
        $sharedFiles = GroupChatFileMedia::whereHas('group_chatting', function($query) use ($group) {
                        $query->where('chatting_group_id', $group->id);
                    })
                    ->with('group_chatting')
                    ->orderBy('created_at', 'desc')
                    ->get();

        $addUsersRoles = Role::select(['id', 'name'])
                    ->with([
                        'users' => function ($user) use ($group) {
                            $user->permission('Group Chatting Read')
                                ->select(['id', 'name'])
                                ->whereIn('id', auth()->user()->user_interactions->pluck('id')) // Users who can interact
                                ->where('id', '!=', auth()->user()->id) // Exclude the current user
                                ->whereStatus('Active') // Only active users
                                ->whereNotIn('id', $group->group_users->pluck('id')) // Exclude users already in the group
                                ->distinct();
                        }
                    ])
                    ->whereHas('users', function ($user) use ($group) {
                        $user->permission('Group Chatting Read')
                            ->whereNotIn('id', $group->group_users->pluck('id')); // Exclude users already in the group
                    })
                    ->distinct()
                    ->get();

        // Clear the cache for unread group messages
        $userId = auth()->id();
        $cacheKey = "unread_group_messages_for_user_{$userId}";
        Cache::forget($cacheKey);

        return view('administration.chatting.group.show', compact(['group', 'roles', 'chatGroups', 'hasChat', 'activeGroup', 'addUsersRoles', 'sharedFiles']));
    }


    /**
     * Fetch Unread group messages for browser notification
     */
    public function fetchUnreadMessagesForBrowser(Request $request)
    {
        try {
            $userId = auth()->id();

            // Get the current group ID from the request if available
            $currentGroupId = $request->input('current_group_id', null);

            // Cache key with user-specific key for uniqueness
            $cacheKey = "unread_group_messages_for_user_{$userId}";

            // Try to get from cache first (5 minute cache)
            $cachedMessages = Cache::get($cacheKey);
            if ($cachedMessages && !$request->input('bypass_cache', false)) {
                return response()->json($cachedMessages);
            }

            // Get unread messages directly from the database with optimized query
            $query = GroupChatting::select(['id', 'chatting_group_id', 'sender_id', 'message', 'created_at'])
                ->whereDoesntHave('readByUsers', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->whereHas('group', function ($query) use ($userId) {
                    // Only include messages from groups the user is a member of
                    $query->whereHas('group_users', function ($q) use ($userId) {
                        $q->where('user_id', $userId);
                    });
                })
                ->where('sender_id', '!=', $userId) // Don't show notifications for own messages
                ->with(['sender:id,name', 'group:id,name']) // Only load necessary fields
                ->latest()
                ->limit(5);

            // If we're on a specific group chat page, exclude messages from that group
            if ($currentGroupId) {
                $query->where('chatting_group_id', '!=', $currentGroupId);
            }

            $unreadMessages = $query->get();

            // Transform the data
            $transformedMessages = $unreadMessages->map(function ($message) {
                return [
                    'id' => $message->id,
                    'chatting_group_id' => $message->chatting_group_id,
                    'group_name' => $message->group->name,
                    'sender_name' => $message->sender->name,
                    'message' => $message->message,
                ];
            });

            // Store in cache for future use (5 minutes)
            Cache::put($cacheKey, $transformedMessages, now()->addMinutes(5));

            return response()->json($transformedMessages);

        } catch (\Exception $e) {
            \Log::error('Error fetching unread group messages for browser notification: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }



    /**
     * Read Browser Notification
     * Marks messages as read and redirects to the group chat
     */
    public function readBrowserNotification($groupId)
    {
        try {
            // Find the group
            $group = ChattingGroup::whereId($groupId)->firstOrFail();

            // Get the authenticated user
            $userId = auth()->id();

            // Mark all unread messages in this group as read for the current user
            $unreadMessages = GroupChatting::where('chatting_group_id', $groupId)
                ->whereDoesntHave('readByUsers', function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                })
                ->get();

            foreach ($unreadMessages as $message) {
                $message->readByUsers()->attach($userId, ['read_at' => now()]);
            }

            // Clear the cache for unread messages
            $cacheKey = "unread_group_messages_for_user_{$userId}";
            Cache::forget($cacheKey);

            return redirect()->route('administration.chatting.group.show', ['group' => $group, 'groupid' => $group->groupid]);
        } catch (Exception $e) {
            Log::error('Error in group readBrowserNotification', [
                'error' => $e->getMessage(),
                'group_id' => $groupId
            ]);

            // Fallback to the group chat index page if there's an error
            return redirect()->route('administration.chatting.group.index')
                ->with('error', 'Could not open the group chat. Error: ' . $e->getMessage());
        }
    }



    /**
     * Store data
     */
    public function store(Request $request)
    {
        /**
         * @var ChattingGroup|null $chatGroup
         */
        $chatGroup = null;

        try {
            DB::transaction(function () use ($request, &$chatGroup) {
                $chatGroup = ChattingGroup::create([
                    'name' => $request->name,
                ]);

                // Assign ChattingGroup creator ID as group_users
                $chatGroup->group_users()->attach(auth()->user()->id, ['role' => 'Admin']);

                // Assign users to the group
                if ($request->has('users')) {
                    $chatGroup->group_users()->attach($request->users);
                }
            });

            toast('Chatting Group Created.', 'success');
            return redirect()->route('administration.chatting.group.show', ['group' => $chatGroup, 'groupid' => $chatGroup->groupid]);
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }



    /**
     * Add Users for Grop Chatting
     */
    public function addUsers(Request $request, ChattingGroup $group, $groupid)
    {
        // dd($request->all(), $group->toArray());
        $request->validate([
            'users' => ['required', 'array'],
            'users.*' => [
                'integer',
                'exists:users,id',
                function ($attribute, $value, $fail) use ($group) {
                    if ($group->group_users()->where('user_id', $value)->exists()) {
                        $fail('The user is already assigned to this group.');
                    }
                },
            ],
        ]);

        try {
            DB::transaction(function() use ($request, $group) {
                if ($request->has('users')) {
                    $group->group_users()->attach($request->users);
                }
            });

            toast('Users Added Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Remove user from Chatting Group
     */
    public function removeUser(ChattingGroup $group, $groupid, User $user)
    {
        try {
            if ($user) {
                $group->group_users()->detach($user->id);
            }

            toast('Users Removed Successfully From The Chatting Group.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChattingGroup $group, $groupid)
    {
        abort_if($group->creator_id !== auth()->user()->id, 403, 'You are not authorized to delete this Chatting Group!');

        try {
            DB::transaction(function () use ($group) {
                // Detach all users from the group before deletion
                $group->group_users()->detach();

                // Delete the group (soft delete or hard delete)
                $group->delete();
            });

            toast('Chatting Group Has Been Deleted Successfully.', 'success');
            return redirect()->route('administration.chatting.group.index');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }



    /**
     * Get Roles with Users
     */
    private function getRoleUsers() {
        $roleUsers = Role::select(['id', 'name'])
                        ->with([
                            'users' => function ($user) {
                                $user->permission('Group Chatting Read')
                                    ->select(['id', 'name'])
                                    ->whereIn('id', auth()->user()->user_interactions->pluck('id'))
                                    ->where('id', '!=', auth()->user()->id)
                                    ->whereStatus('Active');
                            }
                        ])
                        ->whereHas('users', function ($user) {
                            $user->permission('Group Chatting Read');
                        })
                        ->distinct()
                        ->get();

        return $roleUsers;
    }

    /**
     * Get all chatGroups
     */
    private function chatGroups() {
        $chatGroups = Auth::user()->chatting_groups;

        return $chatGroups;
    }
}
