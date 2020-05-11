<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Category\CategoryStoreRequest;
use App\Http\Resources\Category\CategoryCollection;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Category::class, 'category');
    }

    /**
     * Список категорий
     *
     * Get list of categories from storage
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiErrorMessageException
     */
    public function index(Request $request) : JsonResponse
    {
        $categories = Category::query();

        $categories->when(!empty($request->keyword), function ($q) use ($request) {
            return $q->where('name', 'LIKE', $request->keyword . '%');
        });

        $categories = $categories->orderBy('name')->paginate();

        return response()->json([
            'categories' => new CategoryCollection($categories)
        ], 200);
    }

    /**
     * Сохранение категории
     *
     * Store a newly created category in storage.
     *
     * @param CategoryStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiErrorMessageException
     */
    public function store(CategoryStoreRequest $request) : JsonResponse
    {
        $category = Category::create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => __('categories.response.messages.created')
        ], 200);
    }

    /**
     * Информация о категории
     *
     * Display the category.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category) : JsonResponse
    {
        return response()->json([
            'category' => $category
        ], 200);
    }

    /**
     * Форма редактироания категории
     *
     * Show the form for editing the category.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category) : JsonResponse
    {
        return response()->json([
            'category' => $category
        ], 200);
    }

    /**
     * Обновление категории
     *
     * Update the category in storage.
     *
     * @param CategoryStoreRequest $request
     * @param Category $category
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiErrorMessageException
     */
    public function update(CategoryStoreRequest $request, Category $category) : JsonResponse
    {
        $category->update($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => __('categories.response.messages.updated')
        ], 200);
    }

    /**
     * Удаление категории
     *
     * Remove the category from storage.
     *
     * @param Category $category
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiErrorMessageException
     */
    public function destroy(Category $category) : JsonResponse
    {
        $category->delete();

        return response()->json([
            'status' => 'success',
            'message' => __('categories.response.messages.deleted')
        ], 200);
    }
}
