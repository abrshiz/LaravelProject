<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show(User $user)
    {
        $posts = $user->posts()->with(['user', 'comments', 'likes'])->paginate(10);
        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();
        $isFollowing = auth()->check() ? auth()->user()->follows($user) : false;
        
        return view('profile.show', compact('user', 'posts', 'followersCount', 'followingCount', 'isFollowing'));
    }

    public function edit(User $user)
    {
        if ($user->id !== auth()->id()) {
            abort(403);
        }
        
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->id !== auth()->id()) {
            abort(403);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        $user->name = $request->name;
        $user->bio = $request->bio;
        
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }
        
        $user->save();
        
        return redirect()->route('profile.show', $user)->with('success', 'Profile updated!');
    }
}