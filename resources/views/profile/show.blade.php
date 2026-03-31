@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?background=0D8F81&color=fff&size=150&name='.$user->name }}" 
                                 class="rounded-circle img-fluid" style="width: 150px; height: 150px;">
                        </div>
                        <div class="col-md-9">
                            <h2>{{ $user->name }}</h2>
                            <p class="text-muted">{{ $user->email }}</p>
                            @if($user->bio)
                                <p>{{ $user->bio }}</p>
                            @endif
                            
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <strong>{{ $posts->total() }}</strong> posts
                                </div>
                                <div class="col-md-4">
                                    <strong>{{ $followersCount }}</strong> followers
                                </div>
                                <div class="col-md-4">
                                    <strong>{{ $followingCount }}</strong> following
                                </div>
                            </div>
                            
                            @if(auth()->id() !== $user->id)
                                <form action="{{ $isFollowing ? route('users.unfollow', $user) : route('users.follow', $user) }}" 
                                      method="POST" class="mt-3">
                                    @csrf
                                    @if($isFollowing)
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger">Unfollow</button>
                                    @else
                                        <button type="submit" class="btn btn-primary">Follow</button>
                                    @endif
                                </form>
                            @else
                                <a href="{{ route('profile.edit', $user) }}" class="btn btn-outline-primary mt-3">
                                    Edit Profile
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                @foreach($posts as $post)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            @if($post->image)
                                <img src="{{ asset('storage/'.$post->image) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="bg-light text-center p-5">
                                    <p class="text-muted">No image</p>
                                </div>
                            @endif
                            <div class="card-body">
                                <p class="card-text">{{ Str::limit($post->content, 100) }}</p>
                                <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                                <div class="mt-2">
                                    <span>❤️ {{ $post->likes()->count() }}</span>
                                    <span class="ms-2">💬 {{ $post->comments()->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection