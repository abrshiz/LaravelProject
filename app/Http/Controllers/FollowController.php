<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function follow(User $user)
    {
        if (auth()->id() !== $user->id) {
            auth()->user()->following()->attach($user->id);
        }
        
        return back()->with('success', 'You are now following ' . $user->name);
    }

    public function unfollow(User $user)
    {
        if (auth()->id() !== $user->id) {
            auth()->user()->following()->detach($user->id);
        }
        
        return back()->with('success', 'You unfollowed ' . $user->name);
    }
}