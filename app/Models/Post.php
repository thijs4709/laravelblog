<?php

namespace App\Models;

use App\traits\Slugify;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Slugify;

    protected $fillable = ["photo_id", "user_id", "title", "slug", "body"];
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function keywords()
    {
        return $this->morphToMany(Keyword::class, "keywordable");
    }
    public function scopeFilter($query, $searchTerm = null, $searchFields = [])
    {
        //dd($query);
        //als een zoekterm is opgegeven
        if ($searchTerm) {
            //als specifieke velden zijn aangevinkt
            if ($searchFields) {
                //zoek de zoekterm in de opgegeven velden zoeken
                $query->where(function ($query) use (
                    $searchFields,
                    $searchTerm
                ) {
                    foreach ($searchFields as $field) {
                        $query->orWhere(
                            $field,
                            "like",
                            "%" . $searchTerm . "%"
                        );
                    }
                });
            } else {
                //zoek de zoekterm in alle velden die gevuld kunnen worden
                $query->where(function ($query) use ($searchTerm) {
                    $searchFields = (new self())->getFillableFields();
                    foreach ($searchFields as $field) {
                        $query->orWhere(
                            $field,
                            "like",
                            "%" . $searchTerm . "%"
                        );
                    }
                });
            }
        }
        return $query;
    }
    public static function getFillableFields()
    {
        return (new self())->fillable;
    }
}
