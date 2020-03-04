<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleRepo
{
    public function index(Request $request)
    {
        try {
            $articles = Article::query();

            $articles->when($request->keyword, function ($q, $keyword) {
                return $q->where('title', 'LIKE', '%' . $keyword . '%');
            });

            $articles->when($request->publication_date, function ($q, $publication_date) {
                return $q->whereBetween('publication_date', [$publication_date . ' 00:00', $publication_date . ' 23:59']);
            });

            return response()->json([
                'articles' => $articles->latest('publication_date', 'is_publicated')->paginate(10)
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }
    public function create(Request $request) : JsonResponse
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request) : JsonResponse
    {
        try {
            /** Validate Request Params */

            $article = Article::create([
                'title'             => $request->title,
                'description'       => $request->description,
                'image'             => $request->image ?? '',
                'publication_date'  => $request->publication_date,
                'is_publicated'      => $request->is_publicated
            ]);

            // TODO изменить Mediable-отношение
            // TODO прикрутить Mediable-отношение
//            $article->sync($request->media_id);

            return response()->json([
                'status' => 'success',
                'msg'    => __('article_msg_success_create')
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg'    => $error->getMessage()
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Article  $articles
     * @return \Illuminate\Http\JsonResponse
     */
    public function info(Article $article) : JsonResponse
    {
        try {
            return response()->json([
                'article' => $article
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
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
        try {
            /** Validate Request Params */

            $article = $article->update([
                'title'             => $request->title,
                'description'       => $request->description,
                'image'             => $request->image ?? '',
                'publication_date'  => $request->publication_date,
                'is_publicated'     => $request->is_publicated
            ]);

            // TODO прикрутить Mediable-отношение

            return response()->json([
                'status' => 'success',
                'msg'    => __('article_msg_success_update')
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg'    => $error->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Article  $article
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Article $article) : JsonResponse
    {
        try {
            // TODO прикрутить Mediable-отношение


            $article->delete();

            return response()->json([
                'status' => 'success',
                'msg' => __('article_msg_success_delete')
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }
}