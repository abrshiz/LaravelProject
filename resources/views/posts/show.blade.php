@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <img src="{{ $post->user->avatar ? asset('storage/'.$post->user->avatar) : 'https://ui-avatars.com/api/?background=0D8F81&color=fff&name='.$post->user->name }}" 
                                 width="40" height="40" class="rounded-circle me-2">
                            <strong>{{ $post->user->name }}</strong>
                        </div>
                        <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                    </div>
                </div>
                
                <div class="card-body">
                    <p class="card-text">{{ $post->content }}</p>
                    
                    @if($post->image)
                        <img src="{{ asset('storage/'.$post->image) }}" class="img-fluid rounded mb-3">
                    @endif
                    
                    <div class="mb-3">
                        <span>❤️ {{ $post->likes()->count() }} likes</span>
                        <span class="ms-3">💬 {{ $post->comments()->count() }} comments</span>
                    </div>
                    
                    <hr>
                    
                    <h6>Comments</h6>
                    @foreach($post->comments as $comment)
                        <div class="border-bottom pb-2 mb-2">
                            <strong>{{ $comment->user->name }}</strong>
                            <p class="mb-0">{{ $comment->content }}</p>
                            <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                        </div>
                    @endforeach
                    
                    <form action="{{ route('comments.store', $post) }}" method="POST" class="mt-3">
                        @csrf
                        <div class="input-group">
                            <input type="text" name="content" class="form-control" placeholder="Write a comment...">
                            <button type="submit" class="btn btn-primary">Comment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection