<?php

namespace Domain\Article\Actions;

use App\Models\Article;
use Domain\Article\DTOs\UpdateArticleData;

class UpdateArticleAction
{
    public function __invoke(Article $article, UpdateArticleData $dto): Article
    {
        $article->update($dto->toArray());

        return $article;
    }
}