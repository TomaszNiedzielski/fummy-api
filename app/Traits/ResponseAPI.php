<?php

namespace App\Traits;

trait ResponseAPI
{
    public function coreResponse($data, $message, $statusCode, $isSuccess = true)
    {
        if ($isSuccess) {
            return response()->json([
                'message' => $message,
                'code' => $statusCode,
                'data' => $data
            ], $statusCode);
        } else {
            return response()->json([
                'message' => $message,
                'code' => $statusCode,
                'errors' => $data
            ], $statusCode);
        }
    }
    
    public function success($data = null, $message = null, $statusCode = 200)
    {
        return $this->coreResponse($data, $message, $statusCode);
    }

    public function error($message = null, $errors = null, $statusCode = 500)
    {
        return $this->coreResponse($errors, $message, $statusCode, false);
    }
}