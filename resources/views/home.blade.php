@extends('layouts.app')
@section('title') Home @endsection

@section('content')

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg" style="border-radius: 15px; overflow: hidden;">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">Create a New Post</h4>
                </div>
                <div class="card-body" style="background-color:#dca3a322;">
                    <form method="POST" action="{{ route('user.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mb-3">
                            <textarea class="form-control @error('content') is-invalid @enderror" name="content" rows="3" placeholder="Share your thoughts..." style="border-radius: 10px;"></textarea>
                            @error('content')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="post_media" class="form-label" style="font-weight: bold;">Upload Image | Video</label>
                            <div class="input-group">
                                <input type="file" class="form-control" name="file" id="post_media" accept="image/*,video/*" style="border-radius: 5px; border: 1px solid #ced4da;">
                                <button class="btn btn-outline-secondary" type="button" onclick="document.getElementById('post_media').value = '';">Cancel</button>
                            </div>
                            @error('file')
                            <div class="alert alert-danger">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-dark">Post</button>
                    </form>
                </div>
            </div>
            @if(session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
            @endif
            <div id="posts-container" class="mt-4">
                @foreach ($posts as $post)
                <div class="card mt-3 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                    <div class="card-body" style="background-color: #dca3a322;">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('storage/' . $post->user->profile_picture) }}" alt="profile-image" class="img-thumbnail" style="width: 35px; height: 35px; border-radius: 50%; border: 2px solid #180f39;">
                            <a href="{{ route('user.show', ['user' => $post->user->id]) }}" style="text-decoration: none; color: black;">
                                <h6 class="card-title ms-3" style="font-size: 0.9rem; font-weight: bold;">{{ $post->user->name }}</h6>
                            </a>
                            @if(auth()->id() != $post->user->id)
                            <form action="{{ route('user.follow', ['id' => $post->user->id]) }}" method="POST">
                                @csrf
                                <button type="button" class="btn follow-btn {{ auth()->user()->followings()->where('follow_id', $post->user->id)->exists() ? 'btn-outline-danger' : 'btn-outline-primary' }} ms-3" style="font-size: 0.8rem;" data-user-id="{{ $post->user->id }}">
                                    {{ auth()->user()->followings()->where('follow_id', $post->user->id)->exists() ? 'Unfollow' : 'Follow' }}
                                </button>
                            </form>
                            @endif
                        </div>
                        @if($post->file)
                        <div class="media mt-3">
                            @if(Str::endsWith($post->file, ['mp4', 'avi']))
                            <video controls class="img-fluid" style="max-height: 300px; border-radius: 10px;">
                                <source src="{{ asset('storage/' . $post->file) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                            @else
                            <img src="{{ asset('storage/' . $post->file) }}" alt="post-media" class="img-fluid" style="max-height: 300px; border-radius: 10px;">
                            @endif
                        </div>
                        @endif
                        <p class="card-text mt-3" style="font-size: 1rem; line-height: 1.5; font-family:'monospace';">{{ $post->content }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                        <div> 

                        <form id="like-form-{{ $post->id }}" method="POST" action="{{ route('post.like', $post->id) }}"> 
                            @csrf
                                <button type="button" class="btn btn-outline-danger btn-sm like-btn" data-post-id="{{ $post->id }}">
                                    Likes ({{ $post->likedBy()->count() }})
                                </button>
                                <a href="{{route('comment.show', $post->id) }}" name='comment' class="btn btn-outline-primary btn-sm">Comment</a>
                                <a class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#shareModal">Share</a>
                            </form>
                                <!-- Share Modal -->
                                <div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="shareModalLabel">Share</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <a href="https://api.whatsapp.com/send?text={{ urlencode(url('/posts/' . $post->id)) }}" class="btn btn-outline-dark btn-sm"><i class="bi bi-whatsapp"></i></a>
                                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url('/posts/' . $post->id)) }}" class="btn btn-outline-dark btn-sm"><i class="bi bi-facebook"></i></a>
                                                <a href="https://www.instagram.com/direct/new/?text={{ urlencode(url('/posts/' . $post->id)) }}" class="btn btn-outline-dark btn-sm"><i class="bi bi-instagram"></i></a>
                                                <input type="text" class="form-control mt-3" value="{{ url('/posts/' . $post->id) }}" readonly onfocus="this.select();">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <small class="text-muted" style="font-size: 10px">Posted on: {{ $post->created_at->format('M d, Y') }}</small>
                        </div>
                    </div>
                    <div class="card-footer bg-dark">
                        <form id="comment-form-{{ $post->id }}" method="POST" action="{{ route('comment.store') }}">
                            @csrf
                            <div class="input-group">
                                <input type="text" class="form-control comment-input" name="comment" placeholder="Add a comment..." style="border-radius: 10px;">
                                <button type="submit" class="btn btn-outline-light btn-sm mx-2 comment-btn" style="border-radius: 10px;" data-post-id="{{ $post->id }}">Comment</button>
                                <input type="hidden" name="post_id" value="{{ $post->id }}" />
                            </div>
                        </form>
                        {{-- <div class="comments mt-2" id="comments-{{ $post->id }}">
                            @foreach($post->comment as $comment)
                                <div class="comment mb-2 text-white">
                                    <strong class="">{{ $comment->user->name }}:</strong> {{ $comment->comment }}
                                </div>
                            @endforeach
                        </div> --}}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.like-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const postId = this.getAttribute('data-post-id');
        const form = document.getElementById(`like-form-${postId}`);
        const url = form.action;

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ _token: '{{ csrf_token() }}' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.innerText = `Likes (${data.likes_count})`;
            }
        })
        .catch(error => console.log(error));
    });
});

document.querySelectorAll('.comment-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        
        const postId = this.getAttribute('data-post-id');
        const form = document.getElementById(`comment-form-${postId}`);
        const url = form.action;
        const commentInput = form.querySelector('.comment-input');
        const commentsContainer = document.getElementById(`comments-${postId}`);
        
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json', // Ensure Laravel understands JSON response
            },
            body: JSON.stringify({
                _token: '{{ csrf_token() }}',
                comment: commentInput.value,
                post_id: postId
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success){
            commentInput.value = '';
        }})
        .catch(error => console.log(error));
    });
});
    
document.querySelectorAll('.follow-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();

        const userId = this.getAttribute('data-user-id');
        const url = `/user/follow/${userId}`;

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json', // Ensure Laravel understands JSON response
            },
            body: JSON.stringify({
                _token: '{{ csrf_token() }}',
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success){
                // Toggle button appearance
                this.classList.toggle('btn-outline-primary');
                this.classList.toggle('btn-outline-danger');
                
                // Update button text based on follow status
                this.innerText = data.followed ? 'Unfollow' : 'Follow';

            }
        })
        .catch(error => console.error('Error:', error));
    });
});
</script>

@endsection
