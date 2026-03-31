@extends('layouts.app')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }
    
    /* Main container with gradient */
    .dashboard-container {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        padding: 20px 0;
    }
    
    /* Cards with glass morphism effect */
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .glass-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
    }
    
    /* Post input */
    .post-input {
        border: 2px solid #e1e8ed;
        border-radius: 15px;
        padding: 12px 16px;
        font-size: 15px;
        width: 100%;
        transition: all 0.3s;
        background: white;
    }
    
    .post-input:focus {
        border-color: #667eea;
        outline: none;
        box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
    }
    
    /* Buttons */
    .btn-post {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s;
    }
    
    .btn-post:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102,126,234,0.4);
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
        background: rgba(102,126,234,0.1);
        color: #667eea;
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
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #667eea;
    }
    
    .avatar-large {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .post-image {
        max-height: 500px;
        width: 100%;
        object-fit: cover;
        border-radius: 15px;
        cursor: pointer;
        transition: transform 0.3s;
    }
    
    .post-image:hover {
        transform: scale(1.02);
    }
    
    .sidebar-section {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 20px;
        backdrop-filter: blur(10px);
        transition: all 0.3s;
    }
    
    .sidebar-section:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    
    .stat-item {
        text-align: center;
        padding: 12px;
        cursor: pointer;
        transition: all 0.3s;
        border-radius: 12px;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }
    
    .stat-item:hover {
        transform: scale(1.05);
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    .stat-item:hover small {
        color: white;
    }
    
    .modal-custom {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.95);
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
        border-radius: 10px;
    }
    
    .close-modal {
        position: absolute;
        top: 20px;
        right: 35px;
        color: white;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
    }
    
    .close-modal:hover {
        color: #667eea;
    }
    
    /* Gradient text */
    .gradient-text {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    /* Pagination styling */
    .pagination {
        justify-content: center;
    }
    
    .page-link {
        background: rgba(255,255,255,0.9);
        border: none;
        margin: 0 5px;
        border-radius: 10px !important;
        color: #667eea;
    }
    
    .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    
    @media (max-width: 768px) {
        .sidebar {
            display: none;
        }
        .main-feed {
            margin: 0 auto;
        }
    }
</style>

<div class="dashboard-container">
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 20px;">
        <div class="row">
            <!-- Left Sidebar - User Info -->
            <div class="col-lg-3 d-none d-lg-block">
                <div class="sidebar-section">
                    <div class="text-center mb-3">
                        <img src="{{ auth()->user()->avatar ? asset('storage/'.auth()->user()->avatar) : 'https://ui-avatars.com/api/?background=667eea&color=fff&bold=true&size=70&name='.urlencode(auth()->user()->name) }}" 
                             class="avatar-large mb-3">
                        <h5 class="fw-bold mb-0">{{ auth()->user()->name }}</h5>
                        <small class="text-muted">{{ auth()->user()->email }}</small>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span><i class="fas fa-users gradient-text me-2"></i>Friends</span>
                        <span class="fw-bold">{{ auth()->user()->followers()->count() }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center py-2">
                        <span><i class="fas fa-user-plus gradient-text me-2"></i>Following</span>
                        <span class="fw-bold">{{ auth()->user()->following()->count() }}</span>
                    </div>
                    <hr>
                    <a href="{{ route('profile.show', auth()->user()) }}" class="text-decoration-none">
                        <div class="py-2 px-2 rounded text-center" style="transition: all 0.3s; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                            <i class="fas fa-user-circle me-2"></i> View Profile
                        </div>
                    </a>
                </div>
                
                <div class="sidebar-section">
                    <h6 class="fw-bold mb-3"><i class="fas fa-chart-line gradient-text me-2"></i>Your Stats</h6>
                    <div class="row">
                        <div class="col-4">
                            <div class="stat-item">
                                <i class="fas fa-pen fs-4 mb-2 d-block"></i>
                                <div class="fw-bold">{{ auth()->user()->posts()->count() }}</div>
                                <small>Posts</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-item">
                                <i class="fas fa-users fs-4 mb-2 d-block"></i>
                                <div class="fw-bold">{{ auth()->user()->followers()->count() }}</div>
                                <small>Followers</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-item">
                                <i class="fas fa-user-plus fs-4 mb-2 d-block"></i>
                                <div class="fw-bold">{{ auth()->user()->following()->count() }}</div>
                                <small>Following</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main Feed -->
            <div class="col-lg-6 main-feed">
                <!-- Create Post Card -->
                <div class="glass-card p-4 mb-4">
                    <div class="d-flex mb-3">
                        <img src="{{ auth()->user()->avatar ? asset('storage/'.auth()->user()->avatar) : 'https://ui-avatars.com/api/?background=667eea&color=fff&bold=true&size=45&name='.urlencode(auth()->user()->name) }}" 
                             class="avatar me-3">
                        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" style="flex: 1;">
                            @csrf
                            <input type="text" name="content" class="post-input" placeholder="What's on your mind, {{ auth()->user()->name }}?">
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <label class="btn btn-light btn-sm rounded-pill" style="cursor: pointer; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);">
                                    <i class="fas fa-image text-success"></i> Add Photo
                                    <input type="file" name="image" accept="image/*" class="d-none" onchange="this.form.submit()">
                                </label>
                                <button type="submit" class="btn-post">
                                    <i class="fas fa-paper-plane me-1"></i> Post
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Posts Feed -->
                @if(isset($posts) && $posts->count() > 0)
                    @foreach($posts as $post)
                        <div class="glass-card feed-post mb-4" id="post-{{ $post->id }}">
                            <!-- Post Header -->
                            <div class="p-4 pb-2">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                        <a href="{{ route('profile.show', $post->user) }}">
                                            <img src="{{ $post->user->avatar ? asset('storage/'.$post->user->avatar) : 'https://ui-avatars.com/api/?background=667eea&color=fff&bold=true&size=45&name='.urlencode($post->user->name) }}" 
                                                 class="avatar me-3">
                                        </a>
                                        <div>
                                            <a href="{{ route('profile.show', $post->user) }}" class="text-decoration-none">
                                                <h6 class="fw-bold mb-0 gradient-text">{{ $post->user->name }}</h6>
                                            </a>
                                            <small class="text-muted">
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
                            <div class="px-4 pb-2">
                                <p style="font-size: 15px; margin-bottom: 12px;">{{ $post->content }}</p>
                                
                                @if($post->image)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/'.$post->image) }}" class="post-image" onclick="openModal(this.src)">
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Post Stats -->
                            <div class="px-4 py-2 border-top border-bottom">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <i class="fas fa-heart text-danger"></i>
                                        <span class="text-secondary ms-1" id="like-count-{{ $post->id }}">{{ $post->likes()->count() }}</span>
                                        <span class="text-secondary"> likes</span>
                                    </div>
                                    <div>
                                        <i class="far fa-comment gradient-text"></i>
                                        <span class="text-secondary ms-1" id="comment-count-{{ $post->id }}">{{ $post->comments()->count() }}</span>
                                        <span class="text-secondary"> comments</span>
                                    </div>
                                    <div>
                                        <i class="fas fa-share-alt gradient-text"></i>
                                        <span class="text-secondary ms-1">0 shares</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Post Actions -->
                            <div class="px-4 py-2">
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
                            <div id="comment-form-{{ $post->id }}" style="display: none;" class="px-4 pb-3">
                                <form action="{{ route('comments.store', $post) }}" method="POST">
                                    @csrf
                                    <div class="d-flex">
                                        <img src="{{ auth()->user()->avatar ? asset('storage/'.auth()->user()->avatar) : 'https://ui-avatars.com/api/?background=667eea&color=fff&bold=true&size=32&name='.urlencode(auth()->user()->name) }}" 
                                             class="avatar me-2" style="width: 32px; height: 32px;">
                                        <div class="flex-grow-1">
                                            <input type="text" name="content" class="form-control form-control-sm rounded-pill" placeholder="Write a comment...">
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm ms-2 rounded-pill" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">Post</button>
                                    </div>
                                </form>
                            </div>
                            
                            <!-- Comments Preview -->
                            @if($post->comments->count() > 0)
                                <div class="px-4 pb-3 border-top pt-2">
                                    @foreach($post->comments->take(2) as $comment)
                                        <div class="d-flex mt-2">
                                            <img src="{{ $comment->user->avatar ? asset('storage/'.$comment->user->avatar) : 'https://ui-avatars.com/api/?background=667eea&color=fff&bold=true&size=32&name='.urlencode($comment->user->name) }}" 
                                                 class="avatar me-2" style="width: 32px; height: 32px;">
                                            <div class="bg-light rounded-3 p-2" style="flex: 1; background: rgba(102,126,234,0.05) !important;">
                                                <strong class="gradient-text">{{ $comment->user->name }}</strong>
                                                <p style="font-size: 13px; margin-bottom: 0;">{{ $comment->content }}</p>
                                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($post->comments->count() > 2)
                                        <a href="{{ route('posts.show', $post) }}" class="text-decoration-none small mt-2 d-block gradient-text">
                                            View all {{ $post->comments()->count() }} comments
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
                    <div class="glass-card text-center p-5">
                        <i class="fas fa-newspaper" style="font-size: 64px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                        <h5 class="mt-3 fw-bold gradient-text">No posts yet!</h5>
                        <p class="text-muted">Create your first post or follow people to see their updates</p>
                    </div>
                @endif
            </div>
            
            <!-- Right Sidebar -->
            <div class="col-lg-3 d-none d-lg-block">
                <div class="sidebar-section">
                    <h6 class="fw-bold mb-3"><i class="fas fa-fire gradient-text me-2"></i>Trending</h6>
                    <div class="text-center py-3">
                        <i class="fas fa-chart-line" style="font-size: 48px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                        <p class="text-muted small mt-2">Trending topics appear here!</p>
                    </div>
                </div>
                
                <div class="sidebar-section">
                    <h6 class="fw-bold mb-3"><i class="fas fa-lightbulb gradient-text me-2"></i>Pro Tips</h6>
                    <div class="text-center py-2">
                        <i class="fas fa-hand-peace" style="font-size: 48px; color: #667eea;"></i>
                        <p class="text-muted small mt-2">Engage with posts by liking and commenting!</p>
                        <hr>
                        <p class="text-muted small">✨ Share your thoughts</p>
                        <p class="text-muted small">💬 Connect with friends</p>
                        <p class="text-muted small">📸 Post amazing photos</p>
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
function showCommentForm(postId) {
    var form = document.getElementById('comment-form-' + postId);
    if (form.style.display === 'none') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
}

function sharePost(postId) {
    if (navigator.share) {
        navigator.share({
            title: 'Check out this post',
            url: window.location.href + '#post-' + postId
        });
    } else {
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

function openModal(src) {
    document.getElementById('imageModal').style.display = "block";
    document.getElementById('modalImage').src = src;
}

function closeModal() {
    document.getElementById('imageModal').style.display = "none";
}

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
