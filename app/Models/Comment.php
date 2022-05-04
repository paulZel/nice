<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    const IS_ALLOWED = 1;
    const IS_FORBIDDEN = 0;

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function allow()
    {
        $this->status = Comment::IS_ALLOWED;
        $this->save();
    }

    public function forbid()
    {
        $this->status = Comment::IS_FORBIDDEN;
        $this->save();
    }

    public function toggleStatus()
    {
        if($this->status == Comment::IS_FORBIDDEN)
        {
            return $this->allow();
        }
        return $this->forbid();
    }

    public function remove()
    {
        $this->delete();
    }
}
