<?php

namespace App\Http\Requests\Article;

use Illuminate\Foundation\Http\FormRequest;

class IndexArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'filter.category_id' => ['sometimes', 'integer', 'exists:categories,id'],
            'filter.search' => ['sometimes', 'string'],
            'include' => ['sometimes', 'string'],
            'sort' => ['sometimes', 'string'],
        ];
    }
}