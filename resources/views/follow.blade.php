@extends('layouts.app')

@section('title') {{ $user->name }}'s Network @endsection

@section('content')
<div class="container">
    <h2>{{ Auth::check() && Auth::id() == $user->id ? 'Your' : $user->name . "'s" }} Network</h2>
    
    <ul class="nav nav-tabs" id="followTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="followers-tab" data-bs-toggle="tab" data-bs-target="#followers" type="button" role="tab" aria-controls="followers" aria-selected="true">Followers</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="following-tab" data-bs-toggle="tab" data-bs-target="#following" type="button" role="tab" aria-controls="following" aria-selected="false">Following</button>
        </li>
    </ul>
    
    <div class="tab-content" id="followTabsContent">
        <div class="tab-pane fade show active" id="followers" role="tabpanel" aria-labelledby="followers-tab">
            <ul class="list-group mt-3">
                @forelse($followers as $follower)
                    <li class="list-group-item d-flex align-items-center">
                        <img src="{{ asset('storage/' . $follower->profile_picture)}}" alt="{{ $follower->name }}'s profile picture" class="rounded-circle me-2" width="40" height="40">
                        <a class="text-decoration-none text-black" href="{{ route('user.show', $follower->id) }}">{{ $follower->name }}</a>
                    </li>
                @empty
                    <li class="list-group-item">No followers yet.</li>
                @endforelse
            </ul>
        </div>
        <div class="tab-pane fade" id="following" role="tabpanel" aria-labelledby="following-tab">
            <ul class="list-group mt-3">
                @forelse($following as $followed)
                    <li class="list-group-item d-flex align-items-center">
                        <img src="{{ asset('storage/' . $followed->profile_picture) }}" alt="{{ $followed->name }}'s profile picture" class="rounded-circle me-2" width="40" height="40">
                        <a class="text-decoration-none text-black" href="{{ route('user.show', $followed->id) }}">{{ $followed->name }}</a>
                    </li>
                @empty
                    <li class="list-group-item">Not following anyone yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
