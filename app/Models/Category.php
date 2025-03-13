<?php

namespace App\Models;

use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    protected static function newFactory(): CategoryFactory
    {
        return CategoryFactory::new();
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}