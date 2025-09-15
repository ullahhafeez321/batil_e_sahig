@extends('layouts.app')

@section('title', 'Search Results')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <h2 class="mb-4 text-center text-black" style="font-size: 15px">Search Results</h2>

                @if($users->isEmpty())
                    @if (!empty($query))
                        <div class="alert alert-info text-center" role="alert">
                            No users found matching "{{ $query }}".
                        </div>
                    @else
                        <div class="alert alert-info text-center" role="alert">
                            Please enter a search query.
                        </div>
                    @endif
                @else
                    <div class="list-group">
                        @foreach($users as $user)
                            <a href="{{ route('user.show', $user->id) }}" class="list-group-item list-group-item-action mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('storage/' . $user->profile_picture) }}" class="rounded-circle me-3" style="width: 40px; height: 40px;" alt="{{ $user->name }} Profile Picture">
                                    <div>
                                        <h5 class="mb-0">{{ $user->name }}</h5>
                                        <p class="text-muted mb-0 small">{{ $user->bio }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
