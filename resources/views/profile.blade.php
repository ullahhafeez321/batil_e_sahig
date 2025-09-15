@extends('layouts.app')

@section('title') Profile @endsection

@section('content')

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="container mt-4">
    <div class="row justify-content-center">  
        <div class="col-md-10">
            <div class="card shadow-lg" style="border-radius: 15px; overflow: hidden;">
                <div class="card-header bg-gradient text-black">
                    @can('isAdmin')
                    <h4 class="mb-0">Admin Profile</h4>
                    @else
                    <h4 class="mb-0">User Profile</h4>
                    @endcan
                </div>
                <div class="card-body" style="background-color: #fafafa;">
                    <div class="d-flex align-items-center justify-content-center mb-4 position-relative">
                        <div class="profile-image">
                            <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('images/default-profile.png') }}" alt="Profile Picture" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                            <button type="button" class="btn btn-outline-dark translate-middle" data-bs-toggle="modal" data-bs-target="#editProfilePictureModal" style="border-radius: 50%; width: 40px; height: 40px;">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </div>
                    </div>
                    <div class="text-center mb-3">
                        <h3>{{ Auth::user()->name }}</h3>
                        <p>{{ Auth::user()->email }}</p>
                    </div>
                    
                    <div class="d-flex justify-content-around">
                        <div class="text-center">
                            <h5>Posts</h5>
                            <p>{{ Auth::user()->post()->count() }}</p>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('follow', [Auth::user()->id]) }}" class="text-decoration-none text-black">
                                <h5>Followers</h5>
                                <p>{{ Auth::user()->follower()->count() }}</p>
                            </a>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('follow',[Auth::user()->id]) }}" class="text-decoration-none text-black">
                                <h5>Following</h5>
                                <p>{{ Auth::user()->followings()->count() }}</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            @can('isAdmin')
            <div class="card mt-4">
                <div class="card-header bg-gradient text-white">
                    <strong>Manage User Accounts (Admin)</strong>
                </div>
                <div class="card-body" style="background-color: #fafafa;">
                    @foreach (\App\Models\User::all() as $user)
                    @if ($user->id !== Auth::user()->id) {{-- Exclude current user --}}
                    <div class="card mb-3 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : asset('images/default-profile.png') }}" alt="Profile Picture" class="img-thumbnail rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                                <h5 class="ms-3">{{ $user->name }}</h5>
                            </div>
                            <p>{{ $user->email }}</p>
                            <form method="POST" action="{{ route('user.destroy', $user->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">Remove Account</button>
                            </form>
                            <button type="button" class="btn btn-outline-dark btn-sm mt-2" data-bs-toggle="collapse" data-bs-target="#allPosts{{ $user->id }}" aria-expanded="false" aria-controls="allPosts{{ $user->id }}">
                                View Posts
                            </button>
                            <div class="collapse mt-3" id="allPosts{{ $user->id }}">
                                @foreach ($user->post as $post)
                                <div class="card mb-3 shadow-sm" style="border-radius: 15px; overflow: hidden;">
                                    <div class="card-body">
                                        <p>{{ $post->content }}</p>
                                        @if($post->file)
                                        <img src="{{ asset('storage/' . $post->file) }}" alt="post-image" class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
                                        @endif
                                        <div class="d-flex justify-content-between align-items-center mt-3">
                                            <form method="POST" action="{{ route('post.destroy', $post->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                            </form>
                                            {{-- <a href="{{ route('post.edit', $post->id) }}" class="btn btn-outline-dark btn-sm">Edit</a> --}}
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            @endcan
            
            <div class="card mt-4">
                <div class="card-header bg-gradient text-black">
                    <strong>Your Posts</strong>
                </div>
                <div class="card-body" style="background-color: #fafafa;">
                    <div class="row">
                        @foreach (Auth::user()->post as $post)
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm" style="border-radius: 15px; overflow: hidden;">
                                <div class="card-body">
                                    <p>{{ $post->content }}</p>
                                    @if($post->file)
                                    <img src="{{ asset('storage/' . $post->file) }}" alt="post-image" class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
                                    @endif
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <form method="POST" action="{{ route('post.destroy', $post->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                                        </form>
                                        <a href="{{ route('post.edit', $post->id) }}" class="btn btn-outline-dark btn-sm">Edit</a>
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

<!-- Edit Profile Picture Modal -->
<div class="modal fade" id="editProfilePictureModal" tabindex="-1" aria-labelledby="editProfilePictureModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProfilePictureModalLabel">Edit Profile Picture</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('user.update',auth()->user()->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="profile_picture" class="form-label">Upload New Profile Picture</label>
                        <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
