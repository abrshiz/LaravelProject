@extends('layouts.app')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        background: #f0f2f5;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }
    
    .real-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        transition: box-shadow 0.2s ease;
    }
    
    .real-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }
    
    .post-input {
        border: none;
        background: #f0f2f5;
        border-radius: 20px;
        padding: 12px 16px;
        font-size: 15px;
        width: 100%;
        transition: background 0.2s;
    }
    
    .post-input:focus {
        background: #e4e6e9;
        outline: none;
    }
    
    .btn-post {
        background: #1b74e4;
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        transition: background 0.2s;
    }
    
    .btn-post:hover {
        background: #1663c9;
    }
    
    .action-btn {
        background: none;
        border: none;
        color: #65676b;
        font-weight: 600;
        font-size: 14px;
        padding: 8px 16px;
        border-radius: 8px;
        transition: all 0.2s;
        cursor: pointer;
        width: 100%;
    }
    
    .action-btn:hover {
        background: #f0f2f5;
    }
    
    .action-btn i {
        margin-right: 8px;
        font-size: 18px;
    }
    
    .like-active {
        color: #e41e3f;
    }
    
    .like-active i {
        color: #e41e3f;
    }
    
    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
    
    .avatar-large {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        object-fit: cover;
    }
    
    .post-image {
        max-height: 500px;
        width: 100%;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
    }
    
    .sidebar-section {
        background: white;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }
    
    .stat-item {
        text-align: center;
        padding: 12px;
        cursor: pointer;
        transition: background 0.2s;
        border-radius: 8px;
    }
    
    .stat-item:hover {
        background: #f0f2f5;
    }
    
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
        margin-bottom: 16px;
        transition: background 0.2s;
    }
    
    .back-button:hover {
        background: #f0f2f5;
    }
    
    @media (max-width: 768px) {
        .sidebar {
            display: none;
        }
        .main-feed {
            margin: 0 auto;
        }
    }
    
    /* Modal styles */
    .modal-custom {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.9);
        cursor: pointer;
    }
    
    .modal-content {
        margin: auto;
        display: block;
        max-width: 90%;
        max-height: 90%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    
    .close-modal {
        position: absolute;
        top: 20px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
    }
</style>

<div style="background: #f0f2f5; min-height: 100vh;">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 20px;">
        <!-- Back Button -->
        <a href="{{ route('home') }}" class="back-button">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>
        
        <div class="row">
            <!-- Left Sidebar - User Info -->
            <div class="col-lg-3 d-none d-lg-block">
                <div class="sidebar-section">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ auth()->user()->avatar ? asset('storage/'.auth()->user()->avatar) : 'https://ui-avatars.com/api/?background=1b74e4&color=fff&bold=true&size=56&name='.urlencode(auth()->user()->name) }}" 
                             class="avatar-large me-3">
                        <div>
                            <h6 class="fw-bold mb-0" style="font-size: 15px;">{{ auth()->user()->name }}</h6>
                            <small class="text-muted">{{ auth()->user()->email }}</small>
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span class="text-secondary"><i class="fas fa-users me-2"></i>Friends</span>
                        <span class="fw-bold">{{ auth()->user()->followers()->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span class="text-secondary"><i class="fas fa-user-plus me-2"></i>Following</span>
                        <span class="fw-bold">{{ auth()->user()->following()->count() }}</span>
                    </div>
                    <hr class="my-3">
                    <a href="{{ route('profile.show', auth()->user()) }}" class="text-decoration-none text-dark">
                        <div class="py-2 px-2 rounded" style="transition: background 0.2s;" onmouseover="this.style.background='#f0f2f5'" onmouseout="this.style.background='transparent'">
                            <i class="fas fa-user-circle me-2"></i> Your Profile
                        </div>
                    </a>
                </div>
            </div>
            
            <!-- Main Feed -->
            <div class="col-lg-6 main-feed">
                <!-- Create Post Card -->
                <div class="real-card p-3 mb-3">
                    <div class="d-flex mb-3">
                        <img src="{{ auth()->user()->avatar ? asset('storage/'.auth()->user()->avatar) : 'https://ui-avatars.com/api/?background=1b74e4&color=fff&bold=true&size=40&name='.urlencode(auth()->user()->name) }}" 
                             class="avatar me-2">
                        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" style="flex: 1;">
                            @csrf
                            <input type="text" name="content" class="post-input" placeholder="What's on your mind, {{ auth()->user()->name }}?">
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <label class="btn btn-light btn-sm" style="cursor: pointer;">
                                    <i class="fas fa-image text-success"></i> Photo/Video
                                    <input type="file" name="image" accept="image/*" class="d-none" onchange="this.form.submit()">
                                </label>
                                <button type="submit" class="btn-post">Post</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Posts Feed -->
                @if(isset($posts) && $posts->count() > 0)
                    @foreach($posts as $post)
                        <div class="real-card feed-post" id="post-{{ $post->id }}">
                            <!-- Post Header -->
                            <div class="p-3 pb-0">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                        <a href="{{ route('profile.show', $post->user) }}">
                                            <img src="{{ $post->user->avatar ? asset('storage/'.$post->user->avatar) : 'https://ui-avatars.com/api/?background=1b74e4&color=fff&bold=true&size=40&name='.urlencode($post->user->name) }}" 
                                                 class="avatar me-2">
                                        </a>
                                        <div>
                                            <a href="{{ route('profile.show', $post->user) }}" class="text-decoration-none">
                                                <h6 class="fw-bold mb-0 text-dark" style="font-size: 14px;">{{ $post->user->name }}</h6>
                                            </a>
                                            <small class="text-muted" style="font-size: 12px;">
                                                <i class="far fa-clock me-1"></i>{{ $post->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                    @if($post->user_id == auth()->id())
                                        <div class="dropdown">
                                            <button class="btn btn-link text-secondary p-0" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <form action="{{ route('posts.destroy', $post) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Delete this post?')">
                                                            <i class="fas fa-trash me-2"></i>Delete
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Post Content -->
                            <div class="p-3 pt-2">
                                <p style="font-size: 14px; margin-bottom: 12px;">{{ $post->content }}</p>
                                
                                @if($post->image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/'.$post->image) }}" class="post-image" onclick="openModal(this.src)">
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Post Stats -->
                            <div class="px-3 py-2 border-top border-bottom">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <i class="fas fa-heart text-danger"></i>
                                        <span class="text-secondary ms-1" style="font-size: 13px;" id="like-count-{{ $post->id }}">{{ $post->likes()->count() }}</span>
                                    </div>
                                    <div>
                                        <i class="far fa-comment text-secondary"></i>
                                        <span class="text-secondary ms-1" style="font-size: 13px;" id="comment-count-{{ $post->id }}">{{ $post->comments()->count() }}</span>
                                        <span class="text-secondary"> comments</span>
                                    </div>
                                    <div>
                                        <i class="fas fa-share-alt text-secondary"></i>
                                        <span class="text-secondary ms-1" style="font-size: 13px;">0</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Post Actions -->
                            <div class="px-3 py-2">
                                <div class="row">
                                    <div class="col-4">
                                        <form action="{{ route('posts.like', $post) }}" method="POST" id="like-form-{{ $post->id }}">
                                            @csrf
                                            <button type="submit" class="action-btn {{ $post->isLikedBy(auth()->user()) ? 'like-active' : '' }}" id="like-btn-{{ $post->id }}">
                                                <i class="{{ $post->isLikedBy(auth()->user()) ? 'fas' : 'far' }} fa-heart"></i> Like
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-4">
                                        <button class="action-btn" onclick="showCommentForm({{ $post->id }})">
                                            <i class="far fa-comment"></i> Comment
                                        </button>
                                    </div>
                                    <div class="col-4">
                                        <button class="action-btn" onclick="sharePost({{ $post->id }})">
                                            <i class="fas fa-share-alt"></i> Share
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Comment Form (Hidden) -->
                            <div id="comment-form-{{ $post->id }}" style="display: none;" class="px-3 pb-3">
                                <form action="{{ route('comments.store', $post) }}" method="POST">
                                    @csrf
                                    <div class="d-flex">
                                        <img src="{{ auth()->user()->avatar ? asset('storage/'.auth()->user()->avatar) : 'https://ui-avatars.com/api/?background=1b74e4&color=fff&bold=true&size=32&name='.urlencode(auth()->user()->name) }}" 
                                             class="avatar me-2" style="width: 32px; height: 32px;">
                                        <div class="flex-grow-1">
                                            <input type="text" name="content" class="form-control form-control-sm" placeholder="Write a comment..." style="border-radius: 20px;">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm ms-2">Post</button>
                                    </div>
                                </form>
                            </div>
                            
                            <!-- Comments Preview -->
                            @if($post->comments->count() > 0)
                                <div class="px-3 pb-3 border-top pt-2">
                                    @foreach($post->comments->take(2) as $comment)
                                        <div class="d-flex mt-2">
                                            <img src="{{ $comment->user->avatar ? asset('storage/'.$comment->user->avatar) : 'https://ui-avatars.com/api/?background=1b74e4&color=fff&bold=true&size=32&name='.urlencode($comment->user->name) }}" 
                                                 class="avatar me-2" style="width: 32px; height: 32px;">
                                            <div class="bg-light rounded p-2" style="flex: 1;">
                                                <strong style="font-size: 13px;">{{ $comment->user->name }}</strong>
                                                <p style="font-size: 13px; margin-bottom: 0;">{{ $comment->content }}</p>
                                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($post->comments->count() > 2)
                                        <a href="{{ route('posts.show', $post) }}" class="text-decoration-none small mt-2 d-block">
                                            View all {{ $post->comments->count() }} comments
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                    
                    <div class="d-flex justify-content-center mt-4">
                        {{ $posts->links() }}
                    </div>
                @else
                    <div class="real-card text-center p-5">
                        <i class="fas fa-newspaper" style="font-size: 48px; color: #ccc;"></i>
                        <h6 class="mt-3 fw-bold">No posts yet</h6>
                        <p class="text-muted small">Create your first post or follow people to see their updates</p>
                    </div>
                @endif
            </div>
            
            <!-- Right Sidebar -->
            <div class="col-lg-3 d-none d-lg-block">
                <div class="sidebar-section">
                    <h6 class="fw-bold mb-3"><i class="fas fa-chart-line me-2"></i>Your Stats</h6>
                    <div class="row">
                        <div class="col-4">
                            <div class="stat-item">
                                <i class="fas fa-pen fs-4 mb-2 d-block text-primary"></i>
                                <div class="fw-bold">{{ auth()->user()->posts()->count() }}</div>
                                <small class="text-muted">Posts</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-item">
                                <i class="fas fa-users fs-4 mb-2 d-block text-success"></i>
                                <div class="fw-bold">{{ auth()->user()->followers()->count() }}</div>
                                <small class="text-muted">Followers</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-item">
                                <i class="fas fa-user-plus fs-4 mb-2 d-block text-info"></i>
                                <div class="fw-bold">{{ auth()->user()->following()->count() }}</div>
                                <small class="text-muted">Following</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="sidebar-section">
                    <h6 class="fw-bold mb-3"><i class="fas fa-lightbulb me-2"></i>Tips</h6>
                    <div class="text-center py-2">
                        <i class="fas fa-hand-peace" style="font-size: 32px; color: #1b74e4;"></i>
                        <p class="text-muted small mt-2">Engage with posts by liking and commenting!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="imageModal" class="modal-custom" onclick="closeModal()">
    <span class="close-modal">&times;</span>
    <img class="modal-content" id="modalImage">
</div>

<script>
// Show/hide comment form
function showCommentForm(postId) {
    var form = document.getElementById('comment-form-' + postId);
    if (form.style.display === 'none') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
}

// Share post function
function sharePost(postId) {
    if (navigator.share) {
        navigator.share({
            title: 'Check out this post',
            url: window.location.href + '#post-' + postId
        });
    } else {
        // Fallback
        var dummy = document.createElement('input');
        var text = window.location.href + '#post-' + postId;
        dummy.value = text;
        document.body.appendChild(dummy);
        dummy.select();
        document.execCommand('copy');
        document.body.removeChild(dummy);
        alert('Link copied to clipboard!');
    }
}

// Image modal
function openModal(src) {
    document.getElementById('imageModal').style.display = "block";
    document.getElementById('modalImage').src = src;
}

function closeModal() {
    document.getElementById('imageModal').style.display = "none";
}

// AJAX for likes
document.querySelectorAll('form[id^="like-form-"]').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const url = this.action;
        const postId = this.id.split('-')[2];
        
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            const likeBtn = document.getElementById('like-btn-' + postId);
            const likeCount = document.getElementById('like-count-' + postId);
            
            if (data.liked) {
                likeBtn.classList.add('like-active');
                likeBtn.querySelector('i').className = 'fas fa-heart';
            } else {
                likeBtn.classList.remove('like-active');
                likeBtn.querySelector('i').className = 'far fa-heart';
            }
            
            likeCount.textContent = data.count;
        });
    });
});
</script>
@endsection
