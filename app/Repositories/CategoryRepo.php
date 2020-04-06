<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Http\Request;


class CategoryRepo
{
    /**
     * Get list of categories from storage
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Request $request)
    {
        $categories = Category::query();

        $categories->when(!empty($request->keyword), function ($q) use ($request) {
            return $q->where('name', 'LIKE', $request->keyword . '%');
        });

        return $categories->orderBy('name')->paginate();
    }

    /**
     * Store a newly created category in storage.
     *
     * @param array $categoryDate
     */
    public function store(array $categoryDate) : void
    {
        $category = Category::create($categoryDate);
    }

    /**
     * Update the category in storage.
     *
     * @param array $articleData
     * @param Category $category
     */
    public function update(array $articleData, Category $category) : void
    {
        $category->update($articleData);
    }

    /**
     * Remove the category from storage.
     *
     * @param Category $category
     * @throws \Exception
     */
    public function destroy(Category $category) : void
    {
        $category->delete();
    }
}