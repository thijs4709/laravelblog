<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ["post_id", "user_id", "body", "parent_id"];
    //    use SoftDeletes;
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    public function parent()
    {
        return $this->belongsTo(Comment::class, "parent_id");
    }
    public function children()
    {
        return $this->hasMany(Comment::class, "parent_id")->with(["children"]);
    }
}
