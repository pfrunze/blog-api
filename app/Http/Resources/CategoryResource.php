<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this['id'],
            'name' => $this['name'],
            'description' => $this['description'],
            'category_rating' => $this['category_rating'],
            'articles_count' => $this['articles_count'],
            'created_at' => $this['created_at'],
            'updated_at' => $this['updated_at'],
        ];
    }
}
