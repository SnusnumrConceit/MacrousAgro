<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Http\Requests\Product\ProductUploadRequest;
use App\Models\Product;
use App\Repositories\ProductRepo;
use App\Services\MediaService;
use App\Services\ProductService;
use App\Traits\Mediable;
use Illuminate\Http\Request;

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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->product->index($request);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->product->create();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductStoreRequest $request)
    {
        return $this->product->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $this->product->show($product);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return $this->product->show($product);
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
        return $this->product->update($request, $product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        return $this->product->destroy($product);
    }

    public function upload(ProductUploadRequest $request)
    {

    }

    public function removeContent(Request $request)
    {
        $this->media->delete($request->path);

        return response()->json([
            'status' => 'success',
            'msg' => 'Фотография успешно удалена из временного хранилища'
        ], 200);
    }
}
