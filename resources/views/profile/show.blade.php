@extends('layouts.app')

@section('content')
<style>
    .profile-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 40px 0;
    }
    
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.2);
        overflow: hidden;
        margin-bottom: 30px;
    }
    
    .glass-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
    }
    
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 40px;
        text-align: center;
        color: white;
    }
    
    .profile-avatar {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        margin-bottom: 20px;
    }
    
    .stat-box {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        padding: 15px;
        margin: 0 10px;
        transition: all 0.3s;
    }
    
    .stat-box:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-3px);
    }
    
    .post-card {
        transition: all 0.3s;
        height: 100%;
    }
    
    .post-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    
    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 25px;
        text-decoration: none;
        color: #667eea;
        font-weight: 500;
        margin-bottom: 20px;
        transition: all 0.3s;
        border: 1px solid rgba(102,126,234,0.3);
    }
    
    .back-button:hover {
        background: white;
        transform: translateX(-5px);
        color: #764ba2;
    }
    
    .btn-follow {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-follow:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102,126,234,0.4);
    }
    
    .btn-unfollow {
        background: #dc3545;
        color: white;
        border: none;
        padding: 10px 25px;
        border-radius: 25px;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn-unfollow:hover {
        background: #c82333;
        transform: translateY(-2px);
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .glass-card {
        animation: fadeInUp 0.6s ease;
    }
</style>

<div class="profile-container">
    <div class="container" style="max-width: 1000px; margin: 0 auto;">
        <!-- Back Button -->
        <a href="{{ route('home') }}" class="back-button">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>
        
        <div class="glass-card">
            <div class="profile-header">
                <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : 'https://ui-avatars.com/api/?background=ffffff&color=667eea&bold=true&size=150&name='.urlencode($user->name) }}" 
                     class="profile-avatar">
                <h2 class="fw-bold mb-2">{{ $user->name }}</h2>
                <p class="mb-3">
                    <i class="fas fa-envelope me-2"></i> {{ $user->email }}
                </p>
                @if($user->bio)
                    <p class="mb-0">
                        <i class="fas fa-quote-left me-2"></i> {{ $user->bio }}
                    </p>
                @endif
                
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="stat-box">
                            <h3 class="fw-bold mb-0">{{ $posts->total() }}</h3>
                            <small>Posts</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-box">
                            <h3 class="fw-bold mb-0">{{ $followersCount }}</h3>
                            <small>Followers</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-box">
                            <h3 class="fw-bold mb-0">{{ $followingCount }}</h3>
                            <small>Following</small>
                        </div>
                    </div>
                </div>
                
                @if(auth()->id() !== $user->id)
                    <div class="mt-4">
                        <form action="{{ $isFollowing ? route('users.unfollow', $user) : route('users.follow', $user) }}" 
                              method="POST" class="d-inline">
                            @csrf
                            @if($isFollowing)
                                @method('DELETE')
                                <button type="submit" class="btn-unfollow">
                                    <i class="fas fa-user-minus me-2"></i> Unfollow
                                </button>
                            @else
                                <button type="submit" class="btn-follow">
                                    <i class="fas fa-user-plus me-2"></i> Follow
                                </button>
                            @endif
                        </form>
                    </div>
                @else
                    <div class="mt-4">
                        <a href="{{ route('profile.edit', $user) }}" class="btn-follow text-decoration-none d-inline-block">
                            <i class="fas fa-edit me-2"></i> Edit Profile
                        </a>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="row mt-4">
            @foreach($posts as $post)
                <div class="col-md-4 mb-4">
                    <div class="glass-card post-card h-100" style="margin-bottom: 0;">
                        @if($post->image)
                            <img src="{{ asset('storage/'.$post->image) }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="text-center p-4" style="background: linear-gradient(135deg, rgba(102,126,234,0.1) 0%, rgba(118,75,162,0.1) 100%);">
                                <i class="fas fa-image" style="font-size: 48px; color: #667eea;"></i>
                            </div>
                        @endif
                        <div class="p-3">
                            <p class="card-text">{{ Str::limit($post->content, 100) }}</p>
                            <small class="text-muted">
                                <i class="far fa-clock me-1"></i> {{ $post->created_at->diffForHumans() }}
                            </small>
                            <div class="mt-2">
                                <span class="me-3">
                                    <i class="fas fa-heart text-danger"></i> {{ $post->likes()->count() }}
                                </span>
                                <span>
                                    <i class="far fa-comment"></i> {{ $post->comments()->count() }}
                                </span>
                            </div>
                            <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-outline-primary mt-2 w-100" style="border-radius: 20px;">
                                View Post
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection
