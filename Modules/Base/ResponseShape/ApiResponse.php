<?php

namespace Modules\Base\ResponseShape;

use App\Http\Controllers\Controller;

class ApiResponse extends Controller
{
    public static function format($code, $body = [], $message = '', $pagination = null)
    {
        $message = $message ?? '';

        return response()->json([
            'message' => $message,
            'body' => $body ?? [],
            'pagination' => $pagination
        ], $code);
    }
}
