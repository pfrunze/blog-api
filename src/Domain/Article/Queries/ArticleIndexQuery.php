<?php

namespace Domain\Article\Queries;

use App\Http\Requests\Article\IndexArticleRequest;
use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\QueryBuilder;

final class ArticleIndexQuery extends QueryBuilder
{
    public function __construct(IndexArticleRequest $request)
    {
        $query = Article::query();
        parent::__construct($query, $request);

        $this->allowedFilters([
            AllowedFilter::exact('category_id'),
            AllowedFilter::callback('search', function (Builder $query, $value) {
                $searchTerm = trim($value);
                if (!empty($searchTerm)) {
                    $query->whereRaw(
                        'MATCH (title, description) AGAINST (? IN BOOLEAN MODE)',
                        ['+' . $searchTerm]
                    );
                }
            }),
        ])
            ->allowedIncludes([
                'category',
                AllowedInclude::count('commentsCount'),
            ])
            ->defaultSort('-created_at');
    }
}