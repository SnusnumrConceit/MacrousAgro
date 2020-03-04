<?php
/**
 * Created by PhpStorm.
 * User: snusnumr
 * Date: 05.03.20
 * Time: 2:27
 */

namespace App\Repositories;

use App\Http\Requests\Category\CategoryStoreRequest;
use App\Http\Resources\Category\CategoryCollection;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryRepo
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) : JsonResponse
    {
        try {
            /** Validate Input */
            Category::create([
                'name' => $request->name
            ]);

            return response()->json([
                'status' => 'success',
                'msg' => __('category_msg_success_create')
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) : JsonResponse
    {
        try {
            $categories = Category::query();

            $categories->when(! empty($request->keyword), function ($q) use ($request) {
                return $q->where('name', 'LIKE', $request->keyword . '%');
            });

            $categories = $categories->orderBy('name')->paginate(15);

            return response()->json([
                'categories' => new CategoryCollection($categories),
                'status' => 'success'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category) : JsonResponse
    {
        try {
            return response()->json([
                'category' => $category
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return response()->json([
            'category' => $category
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryStoreRequest $request, Category $category) : JsonResponse
    {
        try {
            $category->update([
                'name' => $request->name
            ]);

            return response()->json([
                'status' => 'success',
                'msg' => __('category_msg_success_update')
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category) : JsonResponse
    {
        try {
            $category->delete();

            return response()->json([
                'status' => 'success',
                'msg' => __('category_msg_success_delete')
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }
}