<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Category;
use App\Models\User;
use App\Models\Tag;

class Post extends Model
{
    // use HasFactory;
    use SoftDeletes;

    protected $table = 'posts';
    protected $fillable = ['image', 'title', 'slug', 'content', 'published'];
    protected $guarded = ['user_id', 'category_id'];
    protected $casts = ['published' => 'boolean'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag');
    }
}
