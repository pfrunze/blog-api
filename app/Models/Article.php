<?php

namespace App\Models;

use Database\Factories\ArticleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'user_id', 'title', 'description', 'up_vote_count', 'down_vote_count'];

    protected $appends = ['rating'];

    protected static function newFactory(): ArticleFactory
    {
        return ArticleFactory::new();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function votes()
    {
        return $this->belongsToMany(User::class, 'user_article_likes', 'article_id', 'user_id')
                    ->withPivot('vote_type');
    }

    public function getRatingAttribute()
    {
        return $this->up_vote_count - $this->down_vote_count;
    }
}