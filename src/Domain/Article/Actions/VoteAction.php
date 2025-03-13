<?php

namespace Domain\Article\Actions;

use App\Models\Article;
use Domain\Article\DTOs\VoteData;
use Domain\Article\Enums\VoteTypeEnum;

class VoteAction
{
    public function __invoke(Article $article, VoteData $dto): Article
    {
        $user = auth()->user();
        $existingVote = $article->votes()->where('user_id', $user->id)->first();

        if ($existingVote) {
            if ($existingVote->pivot->vote_type === $dto->voteType) {
                $article->votes()->detach($user->id);

                if ($dto->voteType === VoteTypeEnum::UP->value) {
                    $article->up_vote_count = max(0, $article->up_vote_count - 1);
                } else {
                    $article->down_vote_count = max(0, $article->down_vote_count - 1);
                }
            } else {
                $article->votes()->updateExistingPivot($user->id, ['vote_type' => $dto->voteType]);

                if ($existingVote->pivot->vote_type === VoteTypeEnum::UP->value) {
                    $article->up_vote_count = max(0, $article->up_vote_count - 1);
                    $article->down_vote_count += 1;
                } else {
                    $article->down_vote_count = max(0, $article->down_vote_count - 1);
                    $article->up_vote_count += 1;
                }
            }
        } else {
            $article->votes()->attach($user->id, ['vote_type' => $dto->voteType]);

            if ($dto->voteType === VoteTypeEnum::UP->value) {
                $article->up_vote_count += 1;
            } else {
                $article->down_vote_count += 1;
            }
        }

        $article->save();

        return $article;
    }
}