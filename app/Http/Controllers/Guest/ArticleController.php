<?php

namespace App\Http\Controllers\Guest;

use App\Models\Article;
use App\Http\Resources\Article\Article as ArticleResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Article\ArticleCollection;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $articles = Article::publicated()->paginate();
        return response()->json([
            'articles' => new ArticleCollection($articles)
        ], 200);
    }

    public function show(Article $article)
    {
        return response()->json([
            'article' => new ArticleResource($article)
        ], 200);
    }
}
