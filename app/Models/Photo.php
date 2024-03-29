<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;
    protected $fillable = ["file"];
    protected $uploads = "/assets/";
    //accessor
    //de naam van deze  functie bestaat uit de samenstelling van get en Attribute + de naam van onzze protected fillable in dit voorbeeld dus file
    public function getFileAttribute($photo)
    {
        return $this->uploads . $photo;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function post()
    {
        return $this->hasOne(Post::class);
    }
}
