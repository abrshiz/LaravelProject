@extends('layouts.app')

@section('content')
<style>
    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: white;
        border-radius: 20px;
        text-decoration: none;
        color: #1b74e4;
        font-weight: 500;
        margin-bottom: 20px;
        transition: background 0.2s;
        border: 1px solid #e4e6e9;
    }
    
    .back-button:hover {
        background: #f0f2f5;
    }
</style>

<div style="background: #f0f2f5; min-height: 100vh; padding: 20px 0;">
    <div class="container" style="max-width: 800px; margin: 0 auto;">
        <!-- Back Button -->
        <a href="{{ route('home') }}" class="back-button">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>
        
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pt-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex">
                        <img src="{{ $post->user->avatar ? asset('storage/'.$post->user->avatar) : 'https://ui-avatars.com/api/?background=1b74e4&color=fff&bold=true&size=40&name='.urlencode($post->user->name) }}" 
                             class="rounded-circle me-2" width="50" height="50">
                        <div>
                            <a href="{{ route('profile.show', $post->user) }}" class="text-decoration-none">
                                <h6 class="fw-bold mb-0 text-dark">{{ $post->user->name }}</h6>
                            </a>
                            <small class="text-muted">
                                <i class="far fa-clock"></i> {{ $post->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                    @if($post->user_id == auth()->id())
                        <form action="{{ route('posts.destroy', $post) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link text-danger" onclick="return confirm('Delete this post?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            
            <div class="card-body">
                <p class="card-text">{{ $post->content }}</p>
                
                @if($post->image)
                    <div class="mt-3">
                        <img src="{{ asset('storage/'.$post->image) }}" class="img-fluid rounded">
                    </div>
                @endif
                
                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex gap-4">
                        <div>
                            <i class="fas fa-heart text-danger"></i>
                            <span class="ms-1">{{ $post->likes()->count() }} likes</span>
                        </div>
                        <div>
                            <i class="far fa-comment"></i>
                            <span class="ms-1">{{ $post->comments()->count() }} comments</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Comments Section -->
        <div class="card shadow-sm border-0 mt-3">
            <div class="card-header bg-white border-0">
                <h6 class="fw-bold mb-0">Comments</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('comments.store', $post) }}" method="POST" class="mb-4">
                    @csrf
                    <div class="d-flex">
                        <img src="{{ auth()->user()->avatar ? asset('storage/'.auth()->user()->avatar) : 'https://ui-avatars.com/api/?background=1b74e4&color=fff&bold=true&size=32&name='.urlencode(auth()->user()->name) }}" 
                             class="rounded-circle me-2" width="32" height="32">
                        <div class="flex-grow-1">
                            <input type="text" name="content" class="form-control" placeholder="Write a comment...">
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm ms-2">Post</button>
                    </div>
                </form>
                
                @foreach($post->comments as $comment)
                    <div class="d-flex mb-3">
                        <img src="{{ $comment->user->avatar ? asset('storage/'.$comment->user->avatar) : 'https://ui-avatars.com/api/?background=1b74e4&color=fff&bold=true&size=32&name='.urlencode($comment->user->name) }}" 
                             class="rounded-circle me-2" width="32" height="32">
                        <div class="bg-light rounded p-2 flex-grow-1">
                            <strong>{{ $comment->user->name }}</strong>
                            <p class="mb-1">{{ $comment->content }}</p>
                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                        </div>
                        @if($comment->user_id == auth()->id())
                            <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="ms-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger p-0">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
