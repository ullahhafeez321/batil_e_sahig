<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\Follower;
use App\Http\Controllers\Auth;
class UserController extends Controller
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

            return view("profile");
        }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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

        $post = new Post();
        $post->content = $request->content;
        if( $request->hasFile("file")){
            $path = $request->file("file")->store('images','public');
            $post->file = $path;
        }
        $post->user_id = $request->user()->id; // Assuming there's a user logged in and a user_id column in the posts table
        $post->save();

        return redirect()->back()->with('success','Your Content Has Been Posted');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        if ($id == $request->user()->id) {
            return view('profile');
        } else {
            $user = User::find($id);
            return view('usersprofile', ['user'=> $user]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate(['profile_picture' => 'nullable| image|max:3000']);


        $user = User::find($id);
        // $user->name = $request->name;
        // $user->email = $request->email;    

        if( $request->hasFile('profile_picture')){
            
            $path = public_path('storage/'). $user->profile_picture ;
            if(file_exists($path)){
                @unlink($path);
            }

            $file = $request->profile_picture->store('profilepictures','public');
            $user->profile_picture = $file;
            // return $path;
        }

        $user->save();
       return redirect()->back()->with('success','Your Profile Has Been Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $users = User::find($user->id);
        
        $profile_path = public_path('storage/'. $users->profile_picture);
        if(file_exists($profile_path)){
            @unlink($profile_path);
        }
        
        $users->comment()->delete();
        foreach ($users->post as $postfile) {
            $path = public_path('storage/' . $postfile->file);
            if (file_exists($path)) {
                @unlink($path);
            }
            $postfile->comment()->delete();
            $users->post()->delete();
        }
        
        $users->delete();

        return redirect()->back()->with('success', 'Account and associated posts deleted successfully');
    }

    // 
    
    public function follow(Request $request, $id)
{
    // Find the user to follow/unfollow
    $user = User::findOrFail($id);

    // Get the currently authenticated user
    $authUser = auth()->user();

    // Check if the user is already following the target user
    $isFollowing = $authUser->followings()->where('follow_id', $user->id)->exists();
    $followed = false;

    if ($isFollowing) {
        // Unfollow
        $authUser->followings()->detach($user->id);
    } else {
        // Follow
        $authUser->followings()->attach($user->id);
        $followed = true;
    }

    // Get the updated counts
    $following_count = $user->followings()->count();
    $followers_count = $user->follower()->count();

    // Return a JSON response
    return response()->json([
        'success' => true,
        'followed' => $followed,
        'following_count' => $following_count,
        'followers_count' => $followers_count 
    ]);
}


public function search(Request $request)
{
    $query = $request->input('query');

    if (empty($query)) {
        $users = collect(); // Empty collection if no search query provided
    } else {
        $users = User::where('name', 'LIKE', "%{$query}%")
                     ->orWhere('email', 'LIKE', "%{$query}%")
                     ->get();
    }

    if ($request->ajax()) {
        return response()->json($users);
    }

    return view('searchresult', compact('users', 'query'));
}

}

