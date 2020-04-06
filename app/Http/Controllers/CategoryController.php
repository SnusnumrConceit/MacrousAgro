<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Repositories\CategoryRepo;
use App\Http\Requests\Category\CategoryStoreRequest;
use App\Http\Resources\Category\CategoryCollection;

class CategoryController extends Controller
{
    // TODO добавить политики
    private $category;

    public function __construct(CategoryRepo $category)
    {
        $this->category = $category;
//        $this->authorizeResource(Category::class, 'category');
    }

    /**
     * Get list of categories from storage
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiErrorMessageException
     */
    public function index(Request $request) : JsonResponse
    {
        $categories = $this->category->index($request);

        return response()->json([
            'categories' => new CategoryCollection($categories),
            'status' => 'success'
        ], 200);
    }

    /**
     * Store a newly created category in storage.
     *
     * @param CategoryStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiErrorMessageException
     */
    public function store(CategoryStoreRequest $request) : JsonResponse
    {
        $this->category->store($request->validated());

        return response()->json([
            'status' => 'success',
            'msg' => __('category_msg_success_create')
        ], 200);
    }

    /**
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
     * Update the category in storage.
     *
     * @param CategoryStoreRequest $request
     * @param Category $category
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiErrorMessageException
     */
    public function update(CategoryStoreRequest $request, Category $category) : JsonResponse
    {
        $this->category->update($request->validated(), $category);

        return response()->json([
            'status' => 'success',
            'msg' => __('category_msg_success_update')
        ], 200);
    }

    /**
     * Remove the category from storage.
     *
     * @param Category $category
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiErrorMessageException
     */
    public function destroy(Category $category) : JsonResponse
    {
        $this->category->destroy($category);

        return response()->json([
            'status' => 'success',
            'msg' => __('category_msg_success_delete')
        ], 200);
    }

    /**
     * Get products of category
     *
     * @param Category $category
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function products(Category $category, Request $request) : JsonResponse
    {
        return response()->json([
            'products'      => $category->products()->paginate(),
            'category_name' => $category->name
        ],200);
    }
}
