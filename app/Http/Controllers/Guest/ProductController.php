<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Product\ProductDetail;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
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
