<?php

namespace Domain\Category\Actions;

use Illuminate\Support\Facades\Cache;
use App\Models\Category;

class GetTopCategoriesAction
{
    public function __invoke(): array
    {
        return Cache::remember('top_categories', 3600, function () {
            $maxRatingSubquery = Category::selectRaw('categories.id, MAX(articles.rating) as category_rating')
                ->leftJoin('articles', 'categories.id', '=', 'articles.category_id')
                ->groupBy('categories.id');

            $categories = Category::select('categories.*', 'category_ratings.category_rating')
                ->withCount('articles')
                ->having('articles_count', '>=', 2)
                ->joinSub($maxRatingSubquery, 'category_ratings', function ($join) {
                    $join->on('categories.id', '=', 'category_ratings.id');
                })
                ->orderByDesc('category_ratings.category_rating')
                ->take(5)
                ->get();

            return $categories->map(function ($category) {
                return array_merge($category->toArray(), [
                    'category_rating' => $category->category_rating ?? 0,
                ]);
            })->all();
        });
    }
}