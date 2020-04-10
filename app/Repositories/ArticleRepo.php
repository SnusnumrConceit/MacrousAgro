<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleRepo
{
    /**
     * Get list of articles from storage
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Request $request)
    {
        $articles = Article::query();

        $articles->when($request->keyword, function ($q, $keyword) {
            return $q->where('title', 'LIKE', '%' . $keyword . '%');
        });

        $articles->when($request->is_publicated, function ($q, $is_publicated) {
            return $q->where('is_publicated', $is_publicated);
        });

        $articles->when($request->publication_date, function ($q, $publication_date) {
            return $q->whereBetween('publication_date', [$publication_date . ' 00:00', $publication_date . ' 23:59']);
        });

        return $articles->latest('publication_date', 'is_publicated')->paginate();
    }

    /**
     * Store a newly created article in storage.
     *
     * @param array $articleData
     * @return Article $article
     */
    public function store(array $articleData)
    {
        $article = Article::create($articleData);
        return $article;
    }

    /**
     * Update the article in storage.
     *
     * @param array $articleData
     * @param Article $article
     * @return Article $article
     */
    public function update(array $articleData, Article $article)
    {
        $article->update($articleData);

        return $article;
    }

    /**
     * Remove the article from storage.
     *
     * @param Article $article
     * @throws \Exception
     */
    public function destroy(Article $article) : void
    {
        $article->delete();
    }
}