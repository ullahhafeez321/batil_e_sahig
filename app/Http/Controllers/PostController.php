<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;

class PostController extends Controller
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
    public function index()
    {   
        
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    { 
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'content' => 'required',
            'file'    => 'nullable|mimes:jpg,jpeg,png,mp4,avi|max:3000',
        ],
        [
            'content'   => 'Post Cannot Be Empty!',
            'file'      => 'Maximum file size should be 3MB'
        ]
);

        $post = Post::find($id);
        $post->content = $request->content;
        if( $request->hasFile("file")){
            $path = $request->file("file")->store('images','public');
            $post->file = $path;
        }
        $post->user_id = $request->user()->id; // Assuming there's a user logged in and a user_id column in the posts table
        $post->save();

        return redirect()->back()->with('success','Your Content Has Been Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $userpost = Post::find($post->id);
        $user = auth()->user();
        if ($user->likes()->where('post_id', $post->id)->exists()) {
            $user->likes()->detach($post->id); // Unlike the post if already liked
        }
        
        $path = public_path('storage/'. $userpost->file);
        if(file_exists($path)){
            @unlink($path);
        }
        
        $userpost->comment()->delete();
        $userpost->delete();
        return redirect()->back()->with('success','Deleted Succesfully');
    }

    public function like(Request $request, $id)
{
    $post = Post::findOrFail($id);

    // Toggle like status
    $user = auth()->user();
    if ($post->likedBy()->where('user_id', $user->id)->exists()) {
        $post->likedBy()->detach($user->id);
        $liked = false;
    } else {
        $post->likedBy()->attach($user->id);
        $liked = true;
    }

    // Get the updated likes count
    $likesCount = $post->likedBy()->count();

    // Return a JSON response
    return response()->json([
        'success' => true,
        'liked' => $liked,
        'likes_count' => $likesCount
    ]);
}

}
