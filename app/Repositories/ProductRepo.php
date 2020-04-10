<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\MediaService;
use App\Http\Resources\Product\ProductCollection;

class ProductRepo
{
    private $media;

    public function __construct(MediaService $media)
    {
        $this->media = $media;
    }

    /**
     * Display a listing of the products.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
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

        return $products->latest()->paginate();

//        return response()->json([
//            'products' => Product::paginate(15)
//        ], 200);
    }

    // TODO нужно ли оно? мб объединить с Index
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
     * Store a newly created product in storage.
     *
     * @param array $productData
     * @throws \Exception
     */
    public function store(array $productData)
    {
        $product = Product::create($productData);

        return $product;
    }

    /**
     * Update the product in storage.
     *
     * @param array $productData
     * @param Product $product
     */
    public function update(array $productData, Product $product) : void
    {
        $product->update($productData);
    }

    /**
     * Remove the product from storage.
     *
     * @param Product $product
     * @throws \Exception
     */
    public function destroy(Product $product) : void
    {
        $product->delete();
    }
}