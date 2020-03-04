<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Http\Resources\Product\ProductCollection;
use App\Http\Resources\Product\ProductDetail;
use App\Models\Category;
use App\Models\Mediable;
use App\Models\Product;
use App\Services\MediaService;
use Illuminate\Http\Request;

class ProductRepo
{
    private $media;

    public function __construct(MediaService $media)
    {
        $this->media = $media;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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

        return response()->json([
            'products' => $products->paginate(15)
//            'products' => new ProductCollection($products->paginate(15))
        ], 200);
//        return response()->json([
//            'products' => Product::paginate(15)
//        ], 200);
    }

    public function search(Request $request)
    {
        $products = Product::query();

        $products->when('title', function ($q) use ($request) {
            return $q->where('name', 'LIKE', '%' . $request->title . '%');
        });

        $products->when('created_at', function ($q) use ($request) {
            return $q->whereBetween('created_at', [$request->created_at . ' 00:00', $request->created_at . ' 23:59']);
        });

        return response()->json([
            'products' => new ProductCollection($products->paginate(15))
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductStoreRequest $request)
    {
        $data = $request->validated();

        $product = Product::create($data);

        /* TODO упростить связывание с Media */
        $fileName = substr($request->url, strrpos($request->url, '/'));
        $destination = Product::MEDIA_PATH . $fileName;

        \App\Traits\Mediable::move($request->url, $destination);

        $data['url'] = $fileName;

        if ($request->media_id) {
            $product->media()->create([
                'media_id' => $request->media_id,
                'mediable_id' => $product->id,
                'mediable_type' => self::class
            ]);
        }

        return response()->json([
            'status' => 'success',
            'msg' => __('product_msg_success_create')
        ], 200);
    }

    /**
     * Display the specified resource.
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
     * Show the form for editing the specified resource.
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $data = $request->validated();

        $product->update($data);

        /* TODO упростить связывание с Media */

        return response()->json([
            'status' => 'success',
            'msg' => __('product_msg_success_update')
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        /* TODO упростить связывание с Media */

        return response()->json([
            'status' => 'success',
            'msg' => __('product_msg_success_delete')
        ], 200);
    }
}