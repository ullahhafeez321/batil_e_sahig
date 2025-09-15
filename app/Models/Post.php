<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    public function user()
{
    return $this->belongsTo(User::class);
}

    public function comment(){
        return $this->hasMany(Comment::class,'post_id','id');
    }

    public function likedBy()
{
    return $this->belongsToMany(User::class, 'likes');
}


}


