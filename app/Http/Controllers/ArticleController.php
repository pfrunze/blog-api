<?php

namespace App\Http\Controllers;

use App\Http\Requests\Article\CreateArticleRequest;
use App\Http\Requests\Article\UpdateArticleRequest;
use App\Http\Requests\Article\IndexArticleRequest;
use App\Http\Requests\Article\VoteRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Domain\Article\Actions\CreateArticleAction;
use Domain\Article\Actions\GetMyArticlesAction;
use Domain\Article\Actions\UpdateArticleAction;
use Domain\Article\Actions\VoteAction;
use Domain\Article\DTOs\CreateArticleData;
use Domain\Article\DTOs\UpdateArticleData;
use Domain\Article\DTOs\VoteData;
use Domain\Article\Queries\ArticleIndexQuery;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Gate;

class ArticleController extends Controller
{
    public function index(IndexArticleRequest $request): JsonResource
    {
        $query = new ArticleIndexQuery($request);

        return ArticleResource::collection($query->paginate());
    }

    public function create(CreateArticleRequest $request, CreateArticleAction $createArticle): JsonResource
    {
        $createDto = CreateArticleData::from($request);

        $article = $createArticle($createDto);

        return new ArticleResource($article);
    }

    public function update(
        UpdateArticleRequest $request,
        Article $article,
        UpdateArticleAction $updateArticle
    ): JsonResource
    {
        $updateDto = UpdateArticleData::from($request);

        $article = $updateArticle($article, $updateDto);

        return new ArticleResource($article);
    }

    public function delete(Article $article)
    {
        Gate::authorize('delete', $article);

        $article->delete();

        return response()->noContent();
    }

    public function vote(VoteRequest $request, Article $article, VoteAction $vote): JsonResource
    {
        $voteType = request()->input('vote_type');

        $dto = new VoteData($voteType);
        $article = $vote($article, $dto);
    
        return new ArticleResource($article);
    }

    public function myArticles(GetMyArticlesAction $getMyArticles): JsonResource
    {
        $articles = $getMyArticles();

        return ArticleResource::collection($articles);
    }
}
