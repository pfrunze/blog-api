<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use Domain\Category\Actions\GetTopCategoriesAction;

class CategoryController extends Controller
{
    public function top(GetTopCategoriesAction $getTopCategories)
    {
        $categories = $getTopCategories();

        return CategoryResource::collection(collect($categories));
    }
}