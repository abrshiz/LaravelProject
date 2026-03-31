<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function toggle(Post $post)
{
    $like = Like::where('user_id', auth()->id())
                ->where('post_id', $post->id)
                ->first();

    if ($like) {
        $like->delete();
        $liked = false;
    } else {
        Like::create([
            'user_id' => auth()->id(),
            'post_id' => $post->id
        ]);
        $liked = true;
    }

    if (request()->ajax()) {
        return response()->json([
            'liked' => $liked,
            'count' => $post->likes()->count()
        ]);
    }

    return back();
}
}