<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use Illuminate\Http\Request;

class CommentController extends Controller
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

    public function index(Comment $comment, Request $request)
    {    
        // $posts = Post::all();
        // $id = $request->route('id');
        // $comments = Comment::where('post_id' , $comment->id)->get();
        // dd($comments);
        // return view('comment',['comments' => $comments, 'posts'=> $posts]);
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
     * @param  \App\Http\Requests\StoreCommentRequest  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'comment' => 'required',
    //     ],
    //     [
    //         'comment'   => 'Comment cannot be empty',
    //     ]
    // );
    
    //     $comment = new Comment();
    //     $comment->id = $request->id;
    //     $comment->comment = $request->comment;
    //     $comment->user_id = auth()->user()->id;
    //     $comment->post_id = $request->input('post_id');
    //     $comment->created_at = now();
    //     $comment->updated_at = now();
    //     $comment->save();
    //     return redirect()->route("home")->with("success","Comment Successfully Added");
    // }

    public function store(Request $request)
    {
        $request->validate([
            'comment' => 'required|string|max:255',
            'post_id' => 'required|exists:posts,id'
        ]);
    
        $comment = new Comment();
        $comment->comment = $request->comment;
        $comment->post_id = $request->post_id;
        $comment->user_id = auth()->id();
        $comment->save();
    
        $comment = $comment->fresh('user'); // Ensure you load the user relationship
    
        return response()->json([
            'success' => true,
            'comment' => $comment,
        ]); 
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $posts = Post::where('id', $id)->get();
        $comments = Comment::where('post_id', $id)->get();
        return view('comment',['comments' => $comments, 'posts'=> $posts]);
     
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCommentRequest  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
