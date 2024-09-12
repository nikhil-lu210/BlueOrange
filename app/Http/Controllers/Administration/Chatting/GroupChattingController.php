<?php

namespace App\Http\Controllers\Administration\Chatting;

use Auth;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Chatting\Chatting;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\Chatting\ChattingGroup;
use App\Models\Chatting\GroupChatting;

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

        return view('administration.chatting.group.show', compact(['group', 'roles', 'chatGroups', 'hasChat', 'activeGroup', 'addUsersRoles']));
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
    public function addUsers(Request $request, ChattingGroup $group)
    {
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
    public function removeUser(ChattingGroup $group, User $user)
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
