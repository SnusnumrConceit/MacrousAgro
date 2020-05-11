<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Article;
//use App\Services\ArticleService;
use App\Traits\Mediable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Article\ArticleCollection;
use App\Http\Requests\Article\ArticleStoreRequest;
use App\Http\Requests\Article\ArticleUpdateRequest;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Article::class, 'article');
    }

    /**
     * Список статей
     *
     * List of articles in storage
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \App\Exceptions\ApiErrorMessageException
     */
    public function index(Request $request): JsonResponse
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

        $articles = $articles->latest('publication_date', 'is_publicated')->paginate();

        return response()->json([
            'articles' => new ArticleCollection($articles)
        ], 200);
    }

    /**
     * Создание статьи
     *
     * Store a newly created article in storage.
     *
     * @param ArticleStoreRequest $request
     * @return JsonResponse
     * @throws \App\Exceptions\ApiErrorMessageException
     */
    public function store(ArticleStoreRequest $request) : JsonResponse
    {
        $article = Article::create($request->validated());

        return response()->json([
            'status' => 'success',
            'message'    => __('articles.response.messages.created')
        ], 200);
    }

    /**
     * Показ информации о статье
     *
     * Display the article.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Article $article) : JsonResponse
    {
        return response()->json([
            'article' => $article
        ], 200);
    }

    /**
     * Информация для формы редактирования стотьи
     *
     * Show the form for editing the article.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Article $article) : JsonResponse
    {
        return response()->json([
            'article' => $article
        ], 200);
    }

    /**
     * Обновление статьи
     *
     * Update the article in storage.
     *
     * @param ArticleUpdateRequest $request
     * @param Article $article
     * @return JsonResponse
     */
    public function update(ArticleUpdateRequest $request, Article $article) : JsonResponse
    {
        $article->update($request->validated());

        if (! empty($request->hasFile('image'))) {
            if ($article->medias()->count()) {
                $article->remove($article->medias()->first());
            }

            $media = Mediable::upload(Article::MEDIA_PATH, $request->file('image'), 'articles');

            $article->medias()->sync($media->id);
        }

        return response()->json([
            'status' => 'success',
            'message'    => __('articles.response.messages.updated')
        ], 200);
    }

    /**
     * Удаление статьи
     *
     * Remove the article from storage.
     *
     * @param Article $article
     * @return JsonResponse
     * @throws \App\Exceptions\ApiErrorMessageException
     */
    public function destroy(Article $article) : JsonResponse
    {
        $article->delete();

        return response()->json([
            'status' => 'success',
            'message' => __('articles.response.messages.deleted')
        ], 200);
    }

//    /**
//     * Экспорт статей
//     *
//     * @param Request $request
//     * @return mixed
//     */
//    public function export(Request $request)
//    {
//        return $this->articleService->export($request);
//    }
}
