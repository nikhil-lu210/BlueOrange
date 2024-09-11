<?php

namespace App\Http\Controllers\Administration\Chatting;

use App\Http\Controllers\Controller;
use App\Models\Chatting\GroupChatting;

class GroupChattingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        dd('Group Chatting Index');
    }

    /**
     * Display the specified resource.
     */
    public function show(GroupChatting $group, $groupid)
    {
        dd($group, $groupid);
    }
}
