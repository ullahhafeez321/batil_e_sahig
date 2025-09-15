<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use App\Models\User;
use App\Http\Requests\StoreFollowerRequest;
use App\Http\Requests\UpdateFollowerRequest;

class FollowerController extends Controller
{
        /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
     public function index($id)
     {
         // Fetch the user
         $user = User::findOrFail($id);
     
         // Fetch followers with their names
         $followers = $user->follower()->select('users.id', 'users.name', 'users.profile_picture')->get();
         
         // Fetch followings with their names
         $following = $user->followings()->select('users.id', 'users.name' , 'users.profile_picture')->get();
    
         // Pass followers and followings to the view
         return view('follow', compact('user', 'followers', 'following'));
     }
     
     
     
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFollowerRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFollowerRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Follower  $follower
     * @return \Illuminate\Http\Response
     */
    public function show(Follower $follower)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Follower  $follower
     * @return \Illuminate\Http\Response
     */
    public function edit(Follower $follower)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFollowerRequest  $request
     * @param  \App\Models\Follower  $follower
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFollowerRequest $request, Follower $follower)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Follower  $follower
     * @return \Illuminate\Http\Response
     */
    public function destroy(Follower $follower)
    {
        //
    }
}
