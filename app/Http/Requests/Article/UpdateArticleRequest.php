<?php

namespace App\Http\Requests\Article;

use App\Models\Article;
use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', [
            Article::class,
            $this->article,
        ]);
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ];
    }
}