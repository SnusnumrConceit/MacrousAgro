<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\News;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NewsService
{
    public function create(Request $request) : JsonResponse
    {
        try {
            /** Validate Request Params */

            $news = News::create([
                'title'             => $request->title,
                'description'       => $request->description,
                'image'             => $request->image ?? '',
                'publication_date'  => $request->publication_date,
                'is_publicated'      => $request->is_publicated
            ]);

            return response()->json([
                'status' => 'success',
                'msg'    => __('news_msg_success_create')
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg'    => $error->getMessage()
            ]);
        }
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
            $news = (! isset($request->keyword) && ! isset($request->puplication_date))
                ? News::paginate(10)
                : News::where('title', 'LIKE', $request->keyword . '%')
                    ->where('publication_date', $request->publication_date)
                    ->paginate(10);
            return response()->json([
                'news' => $news
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\JsonResponse
     */
    public function info(int $id) : JsonResponse
    {
        try {
            $news = News::findOrFail($id);

            return response()->json([
                'news' => $news
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
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, int $id) : JsonResponse
    {
        try {
            /** Validate Request Params */

            $news = News::findOrFail($id)->update([
                'title'             => $request->title,
                'description'       => $request->description,
                'image'             => $request->image ?? '',
                'publication_date'  => $request->publication_date,
                'is_publicated'     => $request->is_publicated
            ]);

            return response()->json([
                'status' => 'success',
                'msg'    => __('news_msg_success_update')
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
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id) : JsonResponse
    {
        try {
            $news = News::findOrFail($id);
            $news->delete();

            return response()->json([
                'status' => 'success',
                'msg' => __('news_msg_success_delete')
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }

    public function export(Request $request)
    {
        try {
            $export_type = $request->export_type;

            switch ($export_type) {
                case 'excel':
                    /* написать функциональность */
                    break;

                case 'pdf':
                    break;

                default:
                    throw new \Exception(__('news_msg_error_invalid_export_type'));
            }
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'msg' => $error->getMessage()
            ]);
        }
    }
}
