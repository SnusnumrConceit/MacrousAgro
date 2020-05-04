<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Article;
use App\Services\MediaService;
use App\Traits\Mediable;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Repositories\ArticleRepo;
use App\Http\Resources\Article\ArticleCollection;
use App\Http\Requests\Article\ArticleStoreRequest;
use App\Http\Requests\Article\ArticleUpdateRequest;

class ArticleController extends Controller
{
    // TODO добавить политики
    private $article, $mediaService;

    public function __construct(ArticleRepo $article, MediaService $mediaService)
    {
        $this->article = $article;
        $this->mediaService = $mediaService;
//        $this->authorizeResource(Article::class, 'article');
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
//        $this->authorize('index', Article::class);

        $articles = $this->article->index($request);

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
//        $this->authorize('create', Article::class);

        $media = Mediable::upload(Article::MEDIA_PATH, $request->image, 'articles');

        $article = $this->article->store($request->validated());

        $article->medias()->sync($media->id);

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
//        $this->authorize('view', Article::class);

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
//        $this->authorize('update', Article::class);

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
//        $this->authorize('update', Article::class);

        $this->article->update($request->validated(), $article);

        if (! empty($request->validated()['image'])) {
            if ($article->medias()->count()) {
                $article->remove($article->medias()->first());
            }

            $media = Mediable::upload(Article::MEDIA_PATH, $request->image, 'articles');

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
//        $this->authorize('delete', Article::class);

        $this->article->destroy($article);

        $article->remove($article->medias()->first());

        return response()->json([
            'status' => 'success',
            'message' => __('articles.response.messages.deleted')
        ], 200);
    }

    /**
     * Экспорт статей
     *
     * @param Request $request
     * @return mixed
     */
    public function export(Request $request)
    {
        return $this->article->export($request);
    }
}
