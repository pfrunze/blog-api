<?php

namespace Domain\Article\Actions;

use App\Models\Article;

class GetMyArticlesAction
{
    public function __invoke()
    {
        return Article::where('user_id', auth()->id())->get();
    }
}