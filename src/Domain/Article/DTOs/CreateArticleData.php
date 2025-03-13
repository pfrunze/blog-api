<?php

namespace Domain\Article\DTOs;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
class CreateArticleData extends Data
{
    public function __construct(
        public int $categoryId,
        public string $title,
        public string $description
    ) {}
}
