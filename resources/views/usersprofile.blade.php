@extends('layouts.app')

@section('title') {{ $user->name }}'s Profile @endsection

@section('content')

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg mb-4" style="border-radius: 15px; overflow: hidden;">
                <div class="card-header bg-gradient text-black">
                    <h4 class="mb-0">{{ $user->name }}'s Profile</h4>
                </div>
                <div class="card-body" style="background-color: #fafafa;">
                    <div class="d-flex align-items-center justify-content-center mb-4">
                        <div class="profile-image">
                            <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/default-profile.png') }}" alt="Profile Picture" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                        </div>
                    </div>
                    <div class="text-center mb-3">
                        <h3>{{ $user->name }}</h3>
                        <p>{{ $user->email }}</p>
                    </div>
                    <div class="d-flex justify-content-around">
                        <div class="text-center">
                            <h5>Posts</h5>
                            <p>{{ $user->post()->count() }}</p>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('follow', [$user->id]) }}" class="text-decoration-none text-black">
                                <h5>Followers</h5>
                                <p class="followers-count">{{ $user->follower()->count() }}</p>
                            </a>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('follow',[$user->id]) }}" class="text-decoration-none text-black">
                                <h5>Following</h5>
                                <p class="following-count">{{ $user->followings()->count() }}</p>
                            </a>
                        </div>
                    </div>
                    @if(auth()->id() != $user->id)
                    <form action="{{ route('user.follow', ['id' => $user->id]) }}" method="POST" class="follow-form" id="follow-form-{{ $user->id }} ">
                        @csrf
                        <button data-user-id="{{ $user->id }}" type="submit" class="btn follow-btn {{ auth()->user()->followings()->where('follow_id', $user->id)->exists() ? 'btn-outline-danger' : 'btn-outline-primary' }} ms-3" style="font-size: 0.8rem;" id="follow-btn-{{ $user->id }}">
                            {{ auth()->user()->followings()->where('follow_id', $user->id)->exists() ? 'Unfollow' : 'Follow' }}
                        </button>
                    </form>
                    @endif
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-gradient text-black">
                    <strong>{{ $user->name }}'s Posts</strong>
                </div>
                <div class="card-body " style="background-color: #fafafa;">
                    <div class="row">
                        @foreach ($user->post as $post)
                        <div class="col-md-6 mb-4"> {{-- Increased to col-md-6 for larger posts --}}
                            <div class="card shadow-sm" style="border-radius: 15px; overflow: hidden;">
                                <div class="card-body">
                                    <p>{{ $post->content }}</p>
                                    @if($post->file)
                                    <img src="{{ asset('storage/' . $post->file) }}" alt="post-image" class="img-fluid rounded" style="max-height: 300px; object-fit: cover;"> {{-- Increased max-height for larger images --}}
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <form id="like-form-{{ $post->id }}" method="POST" action="{{ route('post.like', $post->id) }}">
                                            @csrf
                                            <button type="button" class="btn btn-outline-danger btn-sm like-btn" data-post-id="{{ $post->id }}">
                                                Likes ({{ $post->likedBy()->count() }})
                                            </button>
                                            <a href="{{ route('comment.show', $post->id) }}" class="btn btn-outline-primary btn-sm">Comment</a>
                                            <a class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#shareModal">Share</a>
                                        </form>
                                    </div>
                                        <form id="comment-form-{{ $post->id }}" class="comment-form" action="{{ route('comment.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                                            <input type="text" name="comment" class="form-control comment-input" placeholder="Add a comment...">
                                            <button type="submit" class="btn btn-primary btn-sm comment-btn">Post</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

 <!-- Share Modal -->
 <div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareModalLabel">Share</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @foreach ($user->post as $post)
            <div class="modal-body">
                <a href="https://api.whatsapp.com/send?text={{ urlencode(url('/posts/' . $post->id)) }}" class="btn btn-outline-dark btn-sm"><i class="bi bi-whatsapp"></i></a>
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url('/posts/' . $post->id)) }}" class="btn btn-outline-dark btn-sm"><i class="bi bi-facebook"></i></a>
                <a href="https://www.instagram.com/direct/new/?text={{ urlencode(url('/posts/' . $post->id)) }}" class="btn btn-outline-dark btn-sm"><i class="bi bi-instagram"></i></a>
                <input type="text" class="form-control mt-3" value="{{ url('/posts/' . $post->id) }}" readonly onfocus="this.select();">
            </div>
            @endforeach 
        </div>
    </div>
</div>
</div>

<script>
    // Handle like button clicks
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
    
    // Handle comment form submissions
    document.querySelectorAll('.comment-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const postId = this.getAttribute('id').replace('comment-form-', '');
            const url = this.action;
            const commentInput = this.querySelector('.comment-input');
            const commentsContainer = document.getElementById(`comments-${postId}`);
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    _token: '{{ csrf_token() }}',
                    comment: commentInput.value,
                    post_id: postId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    commentInput.value = '';
                    commentsContainer.innerHTML += `
                        <p>${data.comment.content}</p>
                        <small>${data.comment.user.name}</small>
                        <hr>
                    `;
                }
            })
            .catch(error => console.log(error));
        });
    });

    // follow and unfollow handling 
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

                // Update follower count
                const followersCountElement = document.querySelector(`.followers-count`);
                if (followersCountElement) {
                    followersCountElement.innerText = data.followers_count;
                }
                
                // Update following count (if viewing own profile)
                const followingCountElement = document.querySelector('.following-count');
                console.log(data);
                if (followingCountElement) {
                    followingCountElement.innerText = data.following_count;
                }
            }
        })
        .catch(error => console.error('Error:', error));
    });
});

</script>

@endsection
