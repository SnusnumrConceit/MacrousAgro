<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Article\ArticleStoreRequest;
use App\Models\Article;
use App\Repositories\ArticleRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ArticleController extends Controller
{
    private $article;

    public function __construct(ArticleRepo $article)
    {
        $this->article = $article;
//        $this->authorizeResource(Article::class, 'article');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return $this->article->index($request);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ArticleStoreRequest $request) : JsonResponse
    {
        return $this->article->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Article $article) : JsonResponse
    {
        return $this->article->info($article);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Article $article) : JsonResponse
    {
        return $this->article->info($article);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Article $article) : JsonResponse
    {
        return $this->article->update($request, $article);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Article $article) : JsonResponse
    {
        return $this->article->destroy($article);
    }

    public function export(Request $request)
    {
        return $this->article->export($request);
    }
}
