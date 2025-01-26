<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    use HasFactory;
protected $guarded = [];

public function following()
{
    return $this->belongsTo(User::class, 'following_id');
}

public function followers()
{
    return $this->belongsTo(User::class, 'follow_id');
}

public function user()
{
    return $this->belongsTo(User::class, 'follow_id');
}

    
}
