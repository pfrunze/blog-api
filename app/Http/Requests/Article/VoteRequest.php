<?php

namespace App\Http\Requests\Article;

use Domain\Article\Enums\VoteTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'vote_type' => [
                'required',
                Rule::enum(VoteTypeEnum::class),
            ],
        ];
    }
}
