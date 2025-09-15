<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Post;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = [];


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function follower()
    {
        return $this->belongsToMany(User::class, 'followers', 'follow_id', 'following_id');
    }
    
    public function followings()
    {
        return $this->belongsToMany(User::class, 'followers', 'following_id', 'follow_id');
    }
    public function post(){

        return $this->hasMany(Post::class,'user_id','id');
    }
    
    public function comment(){

        return $this->hasMany(Comment::class,'user_id','id');
    }

    public function likes()
{
    return $this->belongsToMany(Post::class, 'likes');
}
    

}
