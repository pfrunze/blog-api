<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use Domain\Category\Actions\GetTopCategoriesAction;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryController extends Controller
{
    public function top(GetTopCategoriesAction $getTopCategories)
    {
        $categories = $getTopCategories();

        return CategoryResource::collection(collect($categories));
    }
}