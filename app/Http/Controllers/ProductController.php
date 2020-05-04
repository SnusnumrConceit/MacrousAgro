<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Traits\Mediable;
use Illuminate\Http\Request;
use App\Http\Resources\Product\ProductDetail;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Requests\Product\ProductUpdateRequest;

class ProductController extends Controller
{
    public function __construct()
    {
//        $this->authorizeResource(Product::class, 'product');
    }

    /**
     * Список товаров
     *
     * Display a listing of the products.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiErrorMessageException
     */
    public function index(Request $request)
    {
        $products = Product::query();

        $products->when($request->keyword, function ($q, $keyword) {
            return $q->where('title', 'LIKE', '%' . $keyword . '%');
        });

        $products->when($request->created_at, function ($q, $created_at) {
            return $q->whereBetween('created_at', [$created_at . ' 00:00', $created_at . ' 23:59']);
        });

        $products->when($request->category, function ($q, $category) {
            return $q->where('category_id', $category);
        });

        $products = $products->latest()->paginate();

        return response()->json([
            'products' => new ProductCollection($products)
        ], 200);
    }

    /**
     * Сохранение товара
     *
     * Store a newly created product in storage.
     *
     * @param ProductStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiErrorMessageException
     */
    public function store(ProductStoreRequest $request)
    {
        $product = Product::create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => __('products.response.messages.created')
        ], 200);
    }

    /**
     * Информация о товаре
     *
     * Display the specified product.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return response()->json([
            'product' => new ProductDetail($product)
        ], 200);
    }

    /**
     * Форма редактирования товара
     *
     * Show the form for editing the product.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return response()->json([
            'product' => new ProductDetail($product),
            'categories' => Category::all()
        ], 200);
    }

    /**
     * Обновление товара
     *
     * Update the product in storage.
     *
     * @param ProductUpdateRequest $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiErrorMessageException
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $product->update($request->validated());

        if (! empty($request->image)) {
            if ($product->medias()->exists()) {
                $product->remove($product->medias()->first());
            }

            $media = Mediable::upload(Product::MEDIA_PATH, $request->image, 'products');

            $product->medias()->sync($media->id);
        }

        return response()->json([
            'status' => 'success',
            'message' => __('products.response.messages.updated')
        ], 200);
    }

    /**
     * Удаление товара
     *
     * Remove the product from storage.
     *
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiErrorMessageException
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => __('products.response.messages.deleted')
        ], 200);
    }

    /**
     * Получение списка случайных товаров для лэндинга
     *
     * Display a random listing of the products for landing page.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function random(Request $request)
    {
        $products = Product::inRandomOrder()->paginate();

        return response()->json([
            'products' => new ProductCollection($products)
        ], 200);
    }
}
