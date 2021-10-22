<?php

namespace App\Http\Traits;

use Carbon\Carbon;

/**
 * 
 */
trait ApiResponse
{
    public function returnError($message = null, $data = [], $status = false, $code = 402)
    {
        return response()->json(
            [
                'message' => $message,
                'data' => $data,
                'status' => $status,
            ],
            $code
        );
    }

    public function returnSuccess($message = null, $data = [], $status = true, $code = 200)
    {
        return response()->json(
            [
                'message' => $message,
                'data' => $data,
                'status' => $status,
            ],
            $code
        );
    }
    public function returnUser($token)
    {
        return $this->returnSuccess(null, [
            'user' => $token,
            'token_type' => 'Bearer',
            'expire_in' => Carbon::now()->addMinutes(config('sanctum.expiration'))
        ]);
    }
}
