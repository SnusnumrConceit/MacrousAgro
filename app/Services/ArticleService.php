<?php
declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\Request;

class ArticleService
{
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
                    throw new \Exception(__('article_msg_error_invalid_export_type'));
            }
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => $error->getMessage()
            ]);
        }
    }
}
