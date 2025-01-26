@extends('layouts.app')
@section('title') Comments @endsection
@section('content')

<div class="comment-section">
    {{-- Comments Section Header --}}
    <h2 class="mb-4" style="font-size: 20px;">Comments</h2>

    @forelse($comments as $comment)
        @if ($loop->first)
            {{-- Post Container - Displayed Only Once --}}
            <div id="posts-container" class="mt-4">
                <div class="card mt-3 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                    <div class="card-body" style="background-color: #dca3a322;">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('storage/' . $comment->post->user->profile_picture) }}" alt="profile-image" class="img-thumbnail" style="width: 35px; height: 35px; border-radius: 50%; border: 2px solid #180f39;">
                            <h6 class="card-title ms-3" style="font-size: 0.9rem; font-weight: bold;">{{ $comment->post->user->name }}</h6>
                        </div>

                        @if($comment->post->file)
                            <div class="media mt-3">
                                @if(Str::endsWith($comment->post->file, ['mp4', 'avi']))
                                    <video controls class="img-fluid" style="max-height: 300px; border-radius: 10px;">
                                        <source src="{{ asset('storage/' . $comment->post->file) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @else
                                    <img src="{{ asset('storage/' . $comment->post->file) }}" alt="post-media" class="img-fluid" style="max-height: 300px; border-radius: 10px;">
                                @endif
                            </div>
                        @endif

                        <p class="card-text mt-3" style="font-size: 1rem; line-height: 1.5; font-family: 'monospace';">{{ $comment->post->content }}</p>
                        <small class="text-muted">Posted on: {{ $comment->post->created_at->format('M d, Y') }}</small>
                    </div>
                </div>
            </div>
        @endif
        
        <br>
        {{-- Comment Section --}}
        <div class="comment mb-4">
            <div class="d-flex align-items-center">
                <img src="{{ asset('storage/' . $comment->user->profile_picture) }}" alt="profile-image" class="img-thumbnail mx-2" style="width: 40px; height: 40px; border-radius: 50%; border: 2px solid #180f39;">
                <div class="ms-3">
                    <h5 class="mb-0" style="font-size: smaller;">{{ $comment->user->name }}</h5>
                    <p class="mb-0" style="font-size: smaller;">{{ $comment->comment }}</p>
                    <small class="text-muted" style="font-size: smaller;">{{ $comment->created_at->format('M d, Y') }}</small>
                </div>
            </div>
        </div>
    @empty
        <p style="font-size: smaller;">No comments available.</p>
    @endforelse
</div>




@endsection
