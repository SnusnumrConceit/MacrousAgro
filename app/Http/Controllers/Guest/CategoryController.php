<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Http\Resources\Category\CategoryCollection;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Список категорий
     *
     * Get list of categories from storage
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $categories = Category::paginate();

        return response()->json([
            'categories' => new CategoryCollection($categories)
        ], 200);
    }

    /**
     * Получить товары категорий
     *
     * Get products of category
     *
     * @param Category $category
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function products(Category $category, Request $request)
    {
        return response()->json([
            'products'      => $category->products()->paginate(),
            'category_name' => $category->name
        ],200);
    }
}
