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
        // dd($activeGroup, $chatGroups[0]);

        return view('administration.chatting.group.show', compact(['group', 'roles', 'chatGroups', 'hasChat', 'activeGroup']));
    }


    /**
     * Store data 
     */
    public function store(Request $request) 
    {
        try {
            DB::transaction(function () use ($request) {
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
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
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
