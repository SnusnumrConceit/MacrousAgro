<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Traits\Mediable;
use App\Services\MediaService;
use App\Repositories\ProductRepo;
use Illuminate\Http\Request;
use App\Http\Resources\Product\ProductDetail;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Requests\Product\ProductUpdateRequest;

class ProductController extends Controller
{
    private $product, $media;

    public function __construct(ProductRepo $product, MediaService $media)
    {
        $this->product = $product;
        $this->media = $media;
//        $this->authorizeResource(Product::class, 'product');
    }

    /**
     * Display a listing of the products.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiErrorMessageException
     */
    public function index(Request $request)
    {
        $products = $this->product->index($request);

        return response()->json([
            'products' => new ProductCollection($products)
        ], 200);
    }

    /**
     * Store a newly created product in storage.
     *
     * @param ProductStoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiErrorMessageException
     */
    public function store(ProductStoreRequest $request)
    {
        $product = $this->product->store($request->validated());

        if ($request->image) {
            $media = Mediable::upload(Product::MEDIA_PATH, $request->image, 'products');

            $product->medias()->sync($media->id);
        }

        return response()->json([
            'status' => 'success',
            'msg' => __('products.response.messages.created')
        ], 200);
    }

    /**
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
     * Update the product in storage.
     *
     * @param ProductUpdateRequest $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiErrorMessageException
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $this->product->update($request->validated(), $product);

        if (! empty($request->image)) {
            if ($product->medias()->exists()) {
                $product->remove($product->medias()->first());
            }

            $media = Mediable::upload(Product::MEDIA_PATH, $request->image, 'products');

            $product->medias()->sync($media->id);
        }

        return response()->json([
            'status' => 'success',
            'msg' => __('products.response.messages.updated')
        ], 200);
    }

    /**
     * Remove the product from storage.
     *
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiErrorMessageException
     */
    public function destroy(Product $product)
    {
        $this->product->destroy($product);

        if ($product->medias()->exists()) {
            $product->remove($product->medias()->first());
        }

        return response()->json([
            'status' => 'success',
            'msg' => __('products.response.messages.deleted')
        ], 200);
    }

    /**
     * Получение списка случайных товаров для лэндинга
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
