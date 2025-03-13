<?php

namespace Domain\Article\DTOs;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class VoteData
{
    public function __construct(
        public string $voteType
    ) {}
}