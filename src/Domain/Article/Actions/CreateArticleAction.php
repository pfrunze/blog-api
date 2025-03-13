<?php

namespace Domain\Article\Actions;

use App\Models\Article;
use App\Models\Category;
use Domain\Article\DTOs\CreateArticleData;

class CreateArticleAction
{
    public function __invoke(CreateArticleData $dto): Article
    {
        $category = Category::findOrFail($dto->categoryId);
        return $category->articles()->create([
            'user_id' => auth()->id(),
            'title' => $dto->title,
            'description' => $dto->description,
            'up_vote_count' => 0,
            'down_vote_count' => 0,
        ]);
    }
}