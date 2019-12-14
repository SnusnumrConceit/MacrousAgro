<?php
declare(strict_types=1);

namespace App\Services;


use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryService
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) : JsonResponse
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
    public function store(Request $request) : JsonResponse
    {
        try {

            $categories = (! isset($request->keyword))
                ? Category::paginate(15)
                : Category::where('name', 'LIKE', $request->keyword . '%')->paginate(15);

            return response()->json([
                'categories' => $categories,
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
    public function info(int $id) : JsonResponse
    {
        try {
            $category = Category::findOrFail($id);

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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id) : JsonResponse
    {
        try {
            /** Validate Input */
            $category = Category::findOrFail($id);

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
    public function destroy(int $id) : JsonResponse
    {
        try {
            $category = Category::findOrFail($id);

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
