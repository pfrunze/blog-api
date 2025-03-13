<?php

namespace Domain\Article\Enums;

enum VoteTypeEnum: string
{
    case UP = 'up';
    case DOWN = 'down';
}